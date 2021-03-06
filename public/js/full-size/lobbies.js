$(function() {
    if ($('#user-find').length > 0) {
        $("#user-find").select2({
            placeholder: 'Find and invite friends/team-mates',
            ajax: {
                url: 'ajax/me/find-acquaintances',
                dataType: 'json',
                delay: 250,
                minimumInputLength: 2,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work,
            templateResult: formatUser, // omitted for brevity, see the source of this page
            templateSelection: formatUserSelection // omitted for brevity, see the source of this page
        });
    }

    $('input[name="stealth"]').change(function() {
        if ($(this).val() == 1) { $('.stealth-mode').slideDown(); }
        else { $('.stealth-mode').slideUp(); }
    });

    if ($('.lobby-view').length > 0) {
        getPlayers();
        setInterval(function() { getPlayers(); }, 10000);
    }

    $('#invite-players-form').submit(function (e) {
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize());
        $('#invite-players').modal('toggle');
        getPlayers();
    });
});

function getPlayers() {
    $.getJSON('ajax/lobby/'+lobbyId+'/player-count', function (data) {
        if (data.error == 'deleted') {
            swal(
                {
                    title: "Whoops, the lobby's gone!",
                    text: "Seems the host deleted this lobby",
                    type: "warning"
                }, function() {
                    window.location.replace(base_url+"dashboard");
                }
            );
        }
        else if (data.count != playerCount) {
            playerCount = data.count;
            $.getJSON('ajax/lobby/'+lobbyId+'/get-players', function (data) {
                $('.players').empty();
                $.each(data, function(i,v) {
                    var img;

                    if (v.img != null) { img = v.img; }
                    else { img = 'img/profile.png'; }

                    var host = hostId == v.id

                    $player = '<div class="row blocklink-wrapper">'+
                        '<div class="col-md-12 blocklink"><a href="users/'+v.slug+'">'+
                        '<div class="col-md-3"><div class="profile-img">'+
                        '<img src="'+img+'" alt="'+v.username+'">'+
                        '</div></div><div class="col-md-9">'+
                        '<h5>'+v.username;

                    if (host) { $player += ' (host)'; }

                    $player += '</h5></div></a></div></div>';
                    $('.players').append($player);
                });
            });

            $('.progress-bar').css('width', (data.count / size * 100)+'%');
            $('.progress-bar .sr-only').text((data.count / size * 100)+'% full');
            $('.progress-bar .ratio').text(data.count+'/'+size);
        }
    });
}

function formatUser (user) {
    if (user.loading) return user.text;
    var img = (user.img != null) ? user.img : 'img/profile.png';

    var markup = '<div class="selectbox-result row blocklink-wrapper persist-cols">'+
        '<div class="col-md-12 blocklink user"><div class="row"><div class="col-md-4"><div class="profile-img">'+
        '<img src="'+img+'" alt="'+user.text+'"></div></div>'+
        '<div class="col-md-8"><p class="name">'+user.text+'</p></div></div></div></div>';

    return markup;
}

function formatUserSelection(user) {
    return user.text;
}