(function ($) {

    var Filter = (function()
    {
        var url = location.hostname;
        var slot = 'http://' + url + '/slot/';

        function Filter(message) {
            var $this = this;

            $(document).ready(function(){
                $this.events();
            });
        }

        Filter.url = '/slots/filter';
        Filter.page = 1;

        var p = Filter.prototype;

        p.events = function () {
            $(document).on('change', '.block-filter select', this._action);
            $(document).on('submit', '.block-filter form', this._action);
            $(document).on('click', 'ul.pagination li a', this._action);
        };

        p._action = function (e) {
            e = e || window.event;

            if(e.type == 'click')
            {
                Filter.page = Number($(this).text());
                $('html, body').scrollTop(0);
            }
            else
            {
                e.target = e.target || e.srcElement;
                Filter.prototype._selectReplace($(e.target));
                Filter.page = 1;
            }

            var
                $this = Filter.prototype,
                data  = $this._getData(),
                link  = $this._getLink(data);

            $.get(link).done(function (response) {
                if(!$.isEmptyObject(response) && !$.isEmptyObject(response.slots)) {
                    $this.update(response);
                }
            });

            return false;
        };

        p.update = function (response) {
            //Desktop version
            $('.slots-block.desk .block-container .games-entry').html(this._html('desk', response.slots.data));

            if($('.slots-block.desk ul.pagination').length) {
                $('.slots-block.desk ul.pagination').replaceWith(response.pagination);
            }
            else {
                $('.slots-block.desk').append(response.pagination);
            }

            //Mobile version
            $('.slots-block.mobile .block-container .games-entry').html(this._html('mobile', response.slots.data));

            if($('.slots-block.mobile ul.pagination').length) {
                $('.slots-block.mobile ul.pagination').replaceWith(response.pagination);
            }
            else {
                $('.slots-block.mobile').append(response.pagination);
            }
        };

        p._html = function (type, games) {
            var $this = this, html = '';
            $.each(games, function (k,game) {
                if(type == 'mobile')
                {
                    html += $this._mobile(game);
                }
                else
                {
                    html += $this._desktop(game);
                }
            });
            return html;
        };

        p._desktop = function (game) {
            var $this = this, html = '';

            html += '<div class="single-game ng-scope" data-slot_id="' + game.id + '">' +
                        '<div class="games-block ng-scope">' +
                            '<span class="games-block__item ng-scope">' +
                                '<img class="games-block__image show-animated" src="' + $this._getImage(game.image) + '" />' +
                            '</span>' +
                            '<div class="games-block__wrap ng-scope">' +
                                '<div class="games-block__action">' +
                                    '<div class="games-block__buttons is-full">' +
                                        '<a href="#" class="open_game games-block__button games-block__button_play-real ng-binding">' + CasinoTranslate.buttons.play + '</a>';

                                        if(game.demo_url)
                                        {
                                            html += '<a href="' + game.demo_url + '" class="games-block__button games-block__button_play-fun ng-binding">' + CasinoTranslate.buttons.demo + '</a>';
                                        }

            html +=                 '</div>' +
                                '</div>' +
                                '<span class="games-block__name ng-binding">' + game.display_name + '</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>';

            // html += '<div class="single-game" data-slot_id="' + game.id + '">' +
            //             '<a href="#" class="open_game">' +
            //                 '<div class="game-preview" style="background: url(' + $this._getImage(game.image) + ') center no-repeat"></div>' +
            //             '</a>' +
            //             '<a href="#" class="open_game"><span class="title">' + game.display_name + '</span></a>' +
            //         '</div>';

            return html;
        };

        p._mobile = function (game) {
            var $this = this, html = '';

            html += '<div class="single-game" data-slot_id="' + game.id + '">' +
                        '<a href="' + (slot + game.id) + '">' +
                            '<div class="game-preview" style="background: url(' + $this._getImage(game.image) + ') center no-repeat"></div>' +
                        '</a>' +
                        '<a href="' + (slot + game.id) + '"><span class="title">' + game.display_name + '</span></a>' +
                    '</div>';

            return html;
        };

        p._getData = function () {
            return {
                page        : Filter.page,
                type        : $("#type_of_game").val(),
                q           : $("input[name='search']").val(),
                category_id : $("#filter_provider").val()
            };
        };

        p._getLink = function (data) {
            var link = Filter.url, i = 0;
            $.each(data, function(key, value) {
                if(i == 0) link += '?' + key + '=' + value;
                else link += '&' + key + '=' + value;
                i++;
            });
            return link;
        };

        p._getImage = function (image) {
            return image.replace(/ /ig, '%20');
        };

        p._selectReplace = function (obj) {
            if(obj.parents('.mobile').length)
            {
                $("#type_of_game").val($("#type_of_game_mobile").val()).trigger('change.select2');
                $("#filter_provider").val($("#filter_provider_mobile").val()).trigger('change.select2');
                $("input[name='search']").val($("input[name='search_mobile']").val());
            }
            else
            {
                $("#type_of_game_mobile").val($("#type_of_game").val()).trigger('change.select2');
                $("#filter_provider_mobile").val($("#filter_provider").val()).trigger('change.select2');
                $("input[name='search_mobile']").val($("input[name='search']").val());
            }
        };

        return Filter;
    }());

    var filter = new Filter();

})(jQuery);