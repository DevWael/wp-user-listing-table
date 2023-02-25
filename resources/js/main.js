(function ($) {
    // Frontend Javascript code goes here.
    'use strict';
    let usersData = {
        /**
         * Overlay dom selector
         */
        overlay: $('.wp-users-table-template .overlay'),
        /**
         * Popup dom selector
         */
        popupContainer: $('.wp-single-user-popup-container'),
        /**
         * Send ajax request to the server to get the user details with the provided user id
         * @param id user id
         */
        getUser: function (id) {
            $.ajax({
                url: usersTableObject.ajaxURL, // The admin-ajax URL.
                type: 'GET', // Request method.
                data: {
                    user_id: id, // User id.
                    action: usersTableObject.action, // Action that will run the WordPress functionality.
                    nonce: usersTableObject.nonce // Nonce string to check against XSS attacks.
                },
                beforeSend: function () {
                    // display the overlay with loading indicator.
                    $(document).trigger('wp-users-table-loading-state');
                },
                success: function (response) {
                    if (response.success) {
                        // Display the popup with user information.
                        $(document).trigger('wp-users-table-success-response', [response.data]);
                        return;
                    }

                    // Display error message if the server returned with an error.
                    $(document).trigger('wp-users-table-error-response', [response.data]);
                },
                error: function () {
                    // Display error message if failed to send the request.
                    $(document).trigger('wp-users-table-error-response', [usersTableObject.i18n.networkFailure]);
                }
            });
        },

        /**
         * Print the user information to html element.
         * @param response
         */
        displayUser: function (response) {
            let html = `<div class="wp-single-user-data"><h3>${usersTableObject.i18n.popupTitle}</h3><hr><div class="wp-row"><table><tr><td>${usersTableObject.i18n.id}:</td><td>${DOMPurify.sanitize(response?.id ?? '')}</td></tr><tr><td>${usersTableObject.i18n.name}:</td><td>${DOMPurify.sanitize(response?.name ?? '')}</td></tr><tr><td>${usersTableObject.i18n.username}:</td><td>${DOMPurify.sanitize(response?.username ?? '')}</td></tr><tr><td>${usersTableObject.i18n.email}:</td><td>${DOMPurify.sanitize(response?.email ?? '')}</td></tr><tr><td>${usersTableObject.i18n.phone}:</td><td>${DOMPurify.sanitize(response?.phone ?? '')}</td></tr><tr><td>${usersTableObject.i18n.address}:</td><td><ul><li><strong>${usersTableObject.i18n.street}:</strong> ${DOMPurify.sanitize(response?.address?.street ?? '')}</li><li><strong>${usersTableObject.i18n.suite}:</strong> ${DOMPurify.sanitize(response?.address?.suite ?? '')}</li><li><strong>${usersTableObject.i18n.city}:</strong> ${DOMPurify.sanitize(response?.address?.city ?? '')}</li><li><strong>${usersTableObject.i18n.zip}:</strong> ${DOMPurify.sanitize(response?.address?.zipcode ?? '')}</li><li><strong>${usersTableObject.i18n.lat}:</strong> ${DOMPurify.sanitize(response?.address?.geo?.lat ?? '')}</li><li><strong>${usersTableObject.i18n.lng}:</strong>${DOMPurify.sanitize(response?.address?.geo?.lng ?? '')}</li></ul></td></tr><tr><td>${usersTableObject.i18n.website}:</td><td><a target="_blank" href="https://${DOMPurify.sanitize(response?.website ?? '')}">${DOMPurify.sanitize(response?.website ?? '')}</a></td></tr><tr><td>${usersTableObject.i18n.company}:</td><td><ul><li><strong>${usersTableObject.i18n.companyName}:</strong> ${DOMPurify.sanitize(response?.company?.name ?? '')}</li><li><strong>${usersTableObject.i18n.catchphrase}:</strong> ${DOMPurify.sanitize(response?.company?.catchPhrase ?? '')}</li><li><strong>${usersTableObject.i18n.business}:</strong> ${DOMPurify.sanitize(response?.company?.bs ?? '')}</li></ul></td></tr></table></div></div>`;
            this.popupContainer.html(html);
        },

        /**
         * Display the html element that contains the user information.
         */
        openPopupSuccess: function () {
            this.overlay.removeClass('loading');
            this.popupContainer.addClass('open');
        },

        /**
         * Open error popup.
         * @param message
         */
        openPopupError: function (message) {
            this.overlay.removeClass('loading');
            this.overlay.html(`<span>${message}</span>`);
            this.overlay.addClass('error');
            setTimeout(function () {
                usersData.closePopup();
            }, 2000);
        },

        /**
         * Close all popups.
         */
        closePopup: function () {
            this.overlay.html();
            this.overlay.removeClass('open error');
            this.popupContainer.removeClass('open');
        }
    };

    /**
     * Event when the user click on the table cell, to get the user id using the data
     * attribute and send an ajax request to get the user data.
     */
    $(document).on('click', 'table.wp-users-table-js td', function (e) {
        usersData.getUser($(this).data('userId'));
    });

    /**
     * Event when the user click on the overlay to close it.
     * Will not work if the overlay is in the loading state.
     */
    $(document).on('click', '.wp-users-table-template .overlay:not(.loading)', function (e) {
        usersData.closePopup();
    });

    /**
     * Event when the user presses the escape key.
     * It will close the popup.
     */
    $(document).keyup(function (e) {
        if (e.key === "Escape") {
            usersData.closePopup();
        }
    });

    /**
     * Event when the request is in loading state.
     * It will show the overlay with a loading indicator.
     */
    $(document).on('wp-users-table-loading-state', function (e) {
        usersData.overlay.addClass('open loading');
    });

    /**
     * Event when the response came with errors.
     * It will show the overlay with an error message.
     */
    $(document).on('wp-users-table-error-response', function (e, data) {
        usersData.openPopupError(data);
    });

    /**
     * Event when the succeed response.
     * It will show the popup of the user information.
     */
    $(document).on('wp-users-table-success-response', function (e, data) {
        usersData.displayUser(data);
        usersData.openPopupSuccess();
    });
})(jQuery);