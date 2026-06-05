(function () {
    const storageKey = 'mytheme_wishlist';

    function readWishlist() {
        try {
            return JSON.parse(localStorage.getItem(storageKey) || '[]');
        } catch (error) {
            return [];
        }
    }

    function writeWishlist(items) {
        localStorage.setItem(storageKey, JSON.stringify(items));
    }

    function getItemData(node) {
        try {
            return JSON.parse(node.getAttribute('data-wishlist-item') || '{}');
        } catch (error) {
            return null;
        }
    }

    function toggleWishlist(item) {
        if (!item || !item.id) {
            return;
        }

        const wishlist = readWishlist();
        const index = wishlist.findIndex((entry) => String(entry.id) === String(item.id));

        if (index >= 0) {
            wishlist.splice(index, 1);
        } else {
            wishlist.push(item);
        }

        writeWishlist(wishlist);
        window.dispatchEvent(new CustomEvent('mytheme:wishlist-updated'));
    }

    function syncWishlistButtons() {
        const wishlist = readWishlist();

        document.querySelectorAll('.wishlist[data-wishlist-item]').forEach((button) => {
            const item = getItemData(button);
            if (!item || !item.id) {
                return;
            }

            const isActive = wishlist.some((entry) => String(entry.id) === String(item.id));
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    }

    function renderWishlistPage() {
        const container = document.querySelector('[data-wishlist-grid]');
        const emptyState = document.querySelector('[data-wishlist-empty]');
        const countNode = document.querySelector('[data-wishlist-count]');

        if (!container || !emptyState) {
            return;
        }

        const items = readWishlist();

        if (countNode) {
            countNode.textContent = items.length + ' item' + (items.length === 1 ? '' : 's') + ' saved';
        }

        container.innerHTML = '';

        if (!items.length) {
            emptyState.hidden = false;
            container.hidden = true;
            return;
        }

        emptyState.hidden = true;
        container.hidden = false;

        items.forEach((item) => {
            const card = document.createElement('div');
            card.className = 'wishlist-card';
            card.innerHTML = [
                '<div class="card-image">',
                '<img src="' + item.image + '" alt="' + item.name + '">',
                '<span class="card-badge">' + (item.category || 'Saved') + '</span>',
                '<button class="card-remove" type="button" aria-label="Remove from wishlist" data-remove-id="' + item.id + '">&#9829;</button>',
                '</div>',
                '<div class="card-info">',
                '<h3 class="card-name">' + item.name + '</h3>',
                '<p class="card-brand">' + (item.brand || '') + '</p>',
                '<span class="card-price">' + (item.price || '') + '</span>',
                '</div>',
            ].join('');
            container.appendChild(card);
        });
    }

    document.addEventListener('click', function (event) {
        const wishlistButton = event.target.closest('.wishlist');
        if (wishlistButton && wishlistButton.hasAttribute('data-wishlist-item')) {
            event.preventDefault();
            toggleWishlist(getItemData(wishlistButton));
            return;
        }

        const removeButton = event.target.closest('[data-remove-id]');
        if (removeButton) {
            event.preventDefault();
            const wishlist = readWishlist().filter((item) => String(item.id) !== String(removeButton.getAttribute('data-remove-id')));
            writeWishlist(wishlist);
            window.dispatchEvent(new CustomEvent('mytheme:wishlist-updated'));
        }
    });

    document.addEventListener('keydown', function (event) {
        if ((event.key !== 'Enter' && event.key !== ' ') || !event.target.classList.contains('wishlist')) {
            return;
        }

        const target = event.target;
        if (!target.hasAttribute('data-wishlist-item')) {
            return;
        }

        event.preventDefault();
        toggleWishlist(getItemData(target));
    });

    document.addEventListener('DOMContentLoaded', renderWishlistPage);
    window.addEventListener('mytheme:wishlist-updated', renderWishlistPage);
    document.addEventListener('DOMContentLoaded', syncWishlistButtons);
    window.addEventListener('mytheme:wishlist-updated', syncWishlistButtons);
})();
