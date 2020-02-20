(function($){
    jQuery( document ).ready( function () {

        // Get posts 
        $('.pagination').toggle();
        var loadMoreButton = $('.load-more__btn'); 
        var loading = false; 
        var postOffset = loadMoreButton.data('offset') ? loadMoreButton.data('offset') : 0;
        var pageIndex = 2; 
        var postType = loadMoreButton.data('postType');
        var cat = loadMoreButton.data('cat');
        var tax = loadMoreButton.data('tax');

        function getNewPost() {
            loading = true; 

            var data = {};

            // no tax 
            if(cat == '' && tax == ''){
                data.per_page =  postOffset;
                data.page = pageIndex;
            }

            // only add a category if there is one (no cat breaks wp api)    
            if (cat && tax == '' || tax == 'category') {
                data.categories = cat; 
            } 

            if (tax) {
                data[tax] = cat; 
            }

            $.ajax( {
                url: wp_api_object.baseUrl + wp_api_object.jsonURI + postType,
                method: 'GET',
                data: data
            } ).done( function( data, textStatus, jqXHR ) {

                if ( Object.keys( jqXHR.responseJSON ).length === 0 && JSON.stringify( jqXHR.responseJSON ) === JSON.stringify([]) ) {
                    toggle_load_btns();
                } else {
    
                    $.each( jqXHR.responseJSON, function( index, post ) {

                        if(postType == 'company'){
                            html = '<article class="grid-item post col-sm-6 col-lg-4">' +
                                        '<div class="card">' +
                                            '<a href="' + post.link + '">' +
                                                '<div class="thumb">' +  
                                                   post.post_grid_data.thumbnail_rendered +  
                                                '</div>' +
                                            '</a>' +
                                            '<div class="content">' +
                                                '<h1><a href="' + post.link + '">' + post.title.rendered + '</a></h1>' +
                                                '<div class="category">' +
                                                    post.post_grid_data.category_rendered +
                                                '</div>' +
                                                post.post_grid_data.exerpt_rendered + 
                                                '<div class="meta">' +
                                                    post.post_grid_data.city_rendered +
                                                    post.post_grid_data.year_rendered +
                                                    '<div class="tags">' +
                                                    post.post_grid_data.main_tag_rendered +   
                                                    post.post_grid_data.tag_toggle_rendered + '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</article>';
                        } else {
                            html = '<article class="grid-item post col-sm-6 col-lg-4">' +
                                        '<div class="card">' +
                                            '<a href="' + post.link + '">' +
                                                '<div class="thumb">' +  
                                                   post.post_grid_data.thumbnail_rendered +  
                                                '</div>' +
                                            '</a>' +
                                            '<div class="content">' +
                                                '<h1><a href="' + post.link + '">' + post.title.rendered + '</a></h1>' +
                                                '<div class="meta">' +
                                                    '<span>' + post.post_grid_data.date_rendered + '</span>' +
                                                        post.post_grid_data.category_rendered + 
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</article>';
                        }

                        $('.post-grid .row').append(html);

                    } );

                    loading = false; 
                    total_pages = parseInt( jqXHR.getResponseHeader('X-WP-TotalPages'), 10 );

                    if ( pageIndex == total_pages ) {
                        toggle_load_btns();
                    }

                    pageIndex++;
                    
                }

            } ).fail( function() {
                console.log( 'AJAX failed' );
            } );

        }

        if (loadMoreButton.length) {

            // Click handler for getting new posts
            loadMoreButton.on('click', function(){
                if (!loading) {
                    getNewPost();
                }
            });

        }
        
        function toggle_load_btns(){
            loadMoreButton.addClass('hidden');
            $('.load-more').addClass('hidden');
        }


        

    } );

})(jQuery);