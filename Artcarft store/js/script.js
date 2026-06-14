$(document).ready(function() {

    $('#header').load('header.html', function() {
        checkSession();
    });

    $('#footer').load('footer.html');

    $('.menu-toggle').click(function() {
        $('.navbar').toggleClass('active');
    });

    function checkSession() {
        $.getJSON('api/session.php', function(res) {
            if (res.loggedIn) {
                $('#auth-links').hide();
                $('#user-links').show();
                $('#cart-count').text(res.cartCount);
                $('#username-display').text(res.username);
            } else {
                $('#auth-links').show();
                $('#user-links').hide();
            }
        });
    }

    $(document).on('click', '.btn-add-cart', function(e) {
        e.preventDefault();
        var productId = $(this).data('id');
        var btn = $(this);

        $.ajax({
            url: 'api/cart.php',
            type: 'POST',
            data: { product_id: productId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    checkSession();
                } else {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        alert(response.message);
                    }
                }
            }
        });
    });

});
