(function () {
    function normalize(value) {
        return String(value || '')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    function getCheckedValues(container) {
        return Array.from(container.querySelectorAll('input[type="checkbox"]:checked')).map(function (input) {
            return normalize(input.parentElement ? input.parentElement.textContent : input.value);
        });
    }

    function cardMatches(card, brandFilters, genderFilters) {
        var tags = (card.getAttribute('data-tags') || '')
            .split(',')
            .map(normalize)
            .filter(Boolean);

        if (brandFilters.length && !brandFilters.some(function (filter) {
            return tags.some(function (tag) {
                return tag.indexOf(filter) !== -1;
            });
        })) {
            return false;
        }

        if (genderFilters.length && !genderFilters.some(function (filter) {
            return tags.some(function (tag) {
                return tag.indexOf(filter) !== -1;
            });
        })) {
            return false;
        }

        return true;
    }

    function applyFilters() {
        document.querySelectorAll('.product_sale_cons_relative').forEach(function (card) {
            var aside = card.closest('.content');
            var filtersRoot = aside ? aside.querySelector('.content_aside') : null;
            var gridRoot = card.closest('.product_sale_cons');
            var root = filtersRoot || gridRoot || document;
            var groups = root.querySelectorAll('.content_aside > div');
            var brandFilters = groups[0] ? getCheckedValues(groups[0]) : [];
            var genderFilters = groups[1] ? getCheckedValues(groups[1]) : [];

            card.hidden = !cardMatches(card, brandFilters, genderFilters);
        });
    }

    document.addEventListener('change', function (event) {
        if (event.target.matches('.content_aside input[type="checkbox"]')) {
            applyFilters();
        }
    });

    document.addEventListener('click', function (event) {
        var wrapper = event.target.closest('.content_aside span');
        if (!wrapper) {
            return;
        }

        var checkbox = wrapper.querySelector('input[type="checkbox"]');
        if (!checkbox) {
            return;
        }

        if (event.target !== checkbox) {
            checkbox.checked = !checkbox.checked;
        }

        applyFilters();
    });

    document.addEventListener('DOMContentLoaded', applyFilters);
})();
