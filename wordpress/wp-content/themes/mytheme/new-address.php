<?php
/*
Template Name: New Address
*/
require_once get_theme_file_path( 'functions_address.php' );
get_header();
?>
<main class="address-page">
    <?php get_template_part( 'nav' ); ?>
    <section class="address-shell">
        <div class="address-card">
            <div class="address-card-head">
                <div>
                    <h1>Add New Address</h1>
                    <p>Enter the address you want to use for checkout. You can save multiple addresses and choose a default later.</p>
                </div>
                <a class="address-back" href="<?php echo esc_url( home_url( '/address/' ) ); ?>">Back to Addresses</a>
            </div>
            <form id="mytheme-address-form" class="address-form">
                <span class="address-field address-span-2"><label for="name">Full Name</label><input type="text" id="name" name="name" placeholder="Jung Cock" required></span>
                <span class="address-field address-span-2"><label for="line1">Address Line 1</label><input type="text" id="line1" name="line1" placeholder="123 Sneaker Street" required></span>
                <span class="address-field address-span-2"><label for="line2">Address Line 2</label><input type="text" id="line2" name="line2" placeholder="Apt 4B"></span>
                <span class="address-field"><label for="city">City</label><input type="text" id="city" name="city" placeholder="New York" required></span>
                <span class="address-field"><label for="state">State</label><input type="text" id="state" name="state" placeholder="NY" required></span>
                <span class="address-field"><label for="postcode">Postal Code</label><input type="text" id="postcode" name="postcode" placeholder="10001" required></span>
                <span class="address-field"><label for="country">Country</label><input type="text" id="country" name="country" value="United States" required></span>
                <span class="address-submit"><button type="submit">Save Address</button></span>
            </form>
        </div>
    </section>
</main>
<script>
(function() {
    var form = document.getElementById('mytheme-address-form');
    var storageKey = 'mytheme_addresses';
    if (!form) return;

    function readAddresses() {
        try {
            return JSON.parse(localStorage.getItem(storageKey) || '[]');
        } catch (e) {
            return [];
        }
    }

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        var data = {
            name: form.name.value.trim(),
            line1: form.line1.value.trim(),
            line2: form.line2.value.trim(),
            city: form.city.value.trim(),
            state: form.state.value.trim(),
            postcode: form.postcode.value.trim(),
            country: form.country.value.trim(),
            isDefault: readAddresses().length === 0
        };

        var addresses = readAddresses();
        addresses.push(data);
        localStorage.setItem(storageKey, JSON.stringify(addresses));
        window.location.href = <?php echo wp_json_encode( home_url( '/address/' ) ); ?>;
    });
})();
</script>
<?php get_footer(); ?>
