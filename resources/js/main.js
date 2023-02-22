(function ($) {
    // Frontend Javascript code goes here.
    'use strict';

    let usersData = {
        getUser: function(id){
            //ajax request
            $.ajax({
                url: usersTableObject.ajaxURL,
                type: 'GET',
                data: {
                    user_id: id,
                    action: usersTableObject.action,
                    nonce: usersTableObject.nonce
                },
                success: function(response) {
                    // Handle successful response
                    console.log(response)
                    console.log(response.data?.names ?? '')
                    usersData.displayUser(response.data);
                },
                error: function(xhr, status, error) {
                    // Handle error
                }
            });

        },
        displayUser: function(response){
            let html = `<div class="wp-single-user-data"><h2>${usersTableObject.i18n.popupTitle}</h2><hr><div class="wp-row"><table><tr><td>${usersTableObject.i18n.id}:</td><td>${response?.id ?? ''}</td></tr><tr><td>${usersTableObject.i18n.name}:</td><td>${response?.name ?? ''}</td></tr><tr><td>${usersTableObject.i18n.username}:</td><td>${response?.username ?? ''}</td></tr><tr><td>${usersTableObject.i18n.email}:</td><td>${response?.email ?? ''}</td></tr><tr><td>${usersTableObject.i18n.phone}:</td><td>${response?.phone ?? ''}</td></tr><tr><td>${usersTableObject.i18n.address}:</td><td><ul><li><strong>${usersTableObject.i18n.street}:</strong> ${response?.address?.street ?? ''}</li><li><strong>${usersTableObject.i18n.suite}:</strong> ${response?.address?.suite ?? ''}</li><li><strong>${usersTableObject.i18n.city}:</strong> ${response?.address?.city ?? ''}</li><li><strong>${usersTableObject.i18n.zip}:</strong> ${response?.address?.zipcode ?? ''}</li><li><strong>${usersTableObject.i18n.lat}:</strong> ${response?.address?.geo?.lat ?? ''}</li><li><strong>${usersTableObject.i18n.lng}:</strong>${response?.address?.geo?.lng ?? ''}</li></ul></td></tr><tr><td>${usersTableObject.i18n.website}:</td><td>${response?.website ?? ''}</td></tr><tr><td>${usersTableObject.i18n.company}:</td><td><ul><li><strong>${usersTableObject.i18n.companyName}:</strong> ${response?.company?.name ?? ''}</li><li><strong>${usersTableObject.i18n.catchphrase}:</strong> ${response?.company?.catchPhrase ?? ''}</li><li><strong>${usersTableObject.i18n.business}:</strong> ${response?.company?.bs ?? ''}</li></ul></td></tr></table></div></div>`;

            $('.wp-single-user-popup-container').html(html)
            usersData.openPopup()
        },
        openPopup: function(){
            $('.wp-users-table-template .overlay').addClass('open');
            $('.wp-single-user-popup-container').addClass('open');
        },
        closePopup: function(){
            $('.wp-users-table-template .overlay').removeClass('open');
            $('.wp-single-user-popup-container').removeClass('open');
        }
    }

    $(document).on('click','table.wp-users-table-js td',function(e) {
        usersData.getUser($(this).data('userId'))
    });
    $(document).on('click','.wp-users-table-template .overlay',function(e) {
        usersData.closePopup()
    });
})(jQuery);