<?php

namespace ItForFree\WpAddons\Helper\Breadcrumbs;

use ItForFree\rusphp\Common\Ui\Breadcrumbs\Breadcrumbs as IffBreadcrumbs;
use ItForFree\WpAddons\Core\Taxonomy\TaxonomyCategory;
use ItForFree\WpAddons\Core\Post\Post;

/**
 * Хлебные крошки для модуля иерахических ссылок
 */
class Breadcrumbs
{
    /**
     * Построит хлебные крошки  для текущей страницы таксономии или для переданного элемента
     * 
     * @param WP_Term $term элемент таксономии
     * @return \ItForFree\rusphp\Common\Ui\Breadcrumbs\Breadcrumbs объект хлебных крошек
     */
    public static function getForTaxonomyItem($term = null)
    {
        if (empty($term)) {    
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
        }
         
        $Bcmrs = new IffBreadcrumbs();
        
        if (!empty($term)) { 
            
            $parents = TaxonomyCategory::getParentsList($term);
            
            
//            vpre($parents, 'Родители');
            
            if(!empty($parents)) {
                // For each parent, create a breadcrumb item
                foreach ($parents as $parent) {
                    $Bcmrs->add($parent->name, static::getUrlForTerm($parent));
                }
            
            }
            // Display the current term in the breadcrumb
            $Bcmrs->current = $term->name;
        }   
        
        return  $Bcmrs;
    }
    
    /**
     * Построит хлебные крошки для переданного поста
     * 
     * @param WP_Post $WP_Post
     * @param string $taxonomyName  имя таксономии
     * @return \ItForFree\rusphp\Common\Ui\Breadcrumbs\Breadcrumbs
     */
    public static function getForPost($WP_Post, $taxonomyName)
    {
        $Term = Post::getFirstTaxonomyItem($WP_Post, $taxonomyName);

        $Bcmrs = static::getForTaxonomyItem($Term);
        
        if (!empty($Term)) {
            $Bcmrs->add($Term->name, static::getUrlForTerm($Term)); // добавляем непосредственного родителя нашей записи
        }
        $Bcmrs->current = $WP_Post->post_title; // устанавливаем имя

        return  $Bcmrs;
    }

    /**
     * Получит ссылку для элемента таксономии 
     * (с учетом иерархии этих элементов)
     * 
     * @param WP_Term $WP_Term
     * @return string  url или пустая строка в случае если $WP_Term пуст
     */
    protected static function getUrlForTerm($WP_Term)
    {
        $url = '';
        if (!empty($WP_Term)) {
            $taxonomySlug = TaxonomyCategory::getTaxonomy($WP_Term)->getUrlName();
    //        vpre(TaxonomyCategory::getTaxonomy($WP_Term), 'taxonomy');
            $hierarhicalPath =  TaxonomyCategory::getHierarhicalUrl($WP_Term);
            $url = "/$taxonomySlug/$hierarhicalPath";
        }
        return  $url;
    }
    
    
    /**
     * Credit: http://www.thatweblook.co.uk/blog/tutorials/tutorial-wordpress-breadcrumb-function/
     * Source: https://gist.github.com/tinotriste/5387124
     * 
     * @global type $post
     */
    public static function the_breadcrumb() {
        $sep = ' > ';
        if (!is_front_page()) {

            // Start the breadcrumb with a link to your homepage
            echo '<div class="breadcrumbs">';
            echo '<a href="';
            echo get_option('home');
            echo '">';
            bloginfo('name');
            echo '</a>' . $sep;

            // Check if the current page is a category, an archive or a single page. If so show the category or archive name.
            if (is_category() || is_single() ){
                the_category('title_li=');
            } elseif (is_archive() || is_single()){
                if ( is_day() ) {
                    printf( __( '%s', 'text_domain' ), get_the_date() );
                } elseif ( is_month() ) {
                    printf( __( '%s', 'text_domain' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'text_domain' ) ) );
                } elseif ( is_year() ) {
                    printf( __( '%s', 'text_domain' ), get_the_date( _x( 'Y', 'yearly archives date format', 'text_domain' ) ) );
                } else {
                    _e( 'Blog Archives', 'text_domain' );
                }
            }

            // If the current page is a single post, show its title with the separator
            if (is_single()) {
                echo $sep;
                the_title();
            }

            // If the current page is a static page, show its title.
            if (is_page()) {
                echo the_title();
            }

            // if you have a static page assigned to be you posts list page. It will find the title of the static page and display it. i.e Home >> Blog
            if (is_home()){
                global $post;
                $page_for_posts_id = get_option('page_for_posts');
                if ( $page_for_posts_id ) { 
                    $post = get_page($page_for_posts_id);
                    setup_postdata($post);
                    the_title();
                    rewind_posts();
                }
            }
            echo '</div>';
        }
    }
    
    /**
     * source @see http://dimox.name/wordpress-breadcrumbs-without-a-plugin/
     * 
    * "Хлебные крошки" для WordPress
    *   автор: Dimox
    *   версия: 2019.03.03
    *   лицензия: MIT
   */
    public static function dimox_breadcrumbs() {

        /* === ОПЦИИ === */
        $text['home'] = 'Главная'; // текст ссылки "Главная"
        $text['category'] = '%s'; // текст для страницы рубрики
        $text['search'] = 'Результаты поиска по запросу "%s"'; // текст для страницы с результатами поиска
        $text['tag'] = 'Записи с тегом "%s"'; // текст для страницы тега
        $text['author'] = 'Статьи автора %s'; // текст для страницы автора
        $text['404'] = 'Ошибка 404'; // текст для страницы 404
        $text['page'] = 'Страница %s'; // текст 'Страница N'
        $text['cpage'] = 'Страница комментариев %s'; // текст 'Страница комментариев N'

        $wrap_before = '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">'; // открывающий тег обертки
        $wrap_after = '</div><!-- .breadcrumbs -->'; // закрывающий тег обертки
        $sep = '<span class="breadcrumbs__separator"> › </span>'; // разделитель между "крошками"
        $before = '<span class="breadcrumbs__current">'; // тег перед текущей "крошкой"
        $after = '</span>'; // тег после текущей "крошки"

        $show_on_home = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
        $show_home_link = 1; // 1 - показывать ссылку "Главная", 0 - не показывать
        $show_current = 1; // 1 - показывать название текущей страницы, 0 - не показывать
        $show_last_sep = 1; // 1 - показывать последний разделитель, когда название текущей страницы не отображается, 0 - не показывать
        /* === КОНЕЦ ОПЦИЙ === */

        global $post;
        $home_url = home_url('/');
        $link = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        $link .= '<a class="breadcrumbs__link" href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>';
        $link .= '<meta itemprop="position" content="%3$s" />';
        $link .= '</span>';
        $parent_id = ( $post ) ? $post->post_parent : '';
        $home_link = sprintf( $link, $home_url, $text['home'], 1 );

        if ( is_home() || is_front_page() ) {

          if ( $show_on_home ) echo $wrap_before . $home_link . $wrap_after;

        } else {

          $position = 0;

          echo $wrap_before;

          if ( $show_home_link ) {
            $position += 1;
            echo $home_link;
          }

          if ( is_category() ) {
            $parents = get_ancestors( get_query_var('cat'), 'category' );
            foreach ( array_reverse( $parents ) as $cat ) {
              $position += 1;
              if ( $position > 1 ) echo $sep;
              echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
            }
            if ( get_query_var( 'paged' ) ) {
              $position += 1;
              $cat = get_query_var('cat');
              echo $sep . sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
              echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
              if ( $show_current ) {
                if ( $position >= 1 ) echo $sep;
                echo $before . sprintf( $text['category'], single_cat_title( '', false ) ) . $after;
              } elseif ( $show_last_sep ) echo $sep;
            }

          } elseif ( is_search() ) {
            if ( get_query_var( 'paged' ) ) {
              $position += 1;
              if ( $show_home_link ) echo $sep;
              echo sprintf( $link, $home_url . '?s=' . get_search_query(), sprintf( $text['search'], get_search_query() ), $position );
              echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
              if ( $show_current ) {
                if ( $position >= 1 ) echo $sep;
                echo $before . sprintf( $text['search'], get_search_query() ) . $after;
              } elseif ( $show_last_sep ) echo $sep;
            }

          } elseif ( is_year() ) {
            if ( $show_home_link && $show_current ) echo $sep;
            if ( $show_current ) echo $before . get_the_time('Y') . $after;
            elseif ( $show_home_link && $show_last_sep ) echo $sep;

          } elseif ( is_month() ) {
            if ( $show_home_link ) echo $sep;
            $position += 1;
            echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position );
            if ( $show_current ) echo $sep . $before . get_the_time('F') . $after;
            elseif ( $show_last_sep ) echo $sep;

          } elseif ( is_day() ) {
            if ( $show_home_link ) echo $sep;
            $position += 1;
            echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position ) . $sep;
            $position += 1;
            echo sprintf( $link, get_month_link( get_the_time('Y'), get_the_time('m') ), get_the_time('F'), $position );
            if ( $show_current ) echo $sep . $before . get_the_time('d') . $after;
            elseif ( $show_last_sep ) echo $sep;

          } elseif ( is_single() && ! is_attachment() ) {
            if ( get_post_type() != 'post' ) {
              $position += 1;
              $post_type = get_post_type_object( get_post_type() );
              if ( $position > 1 ) echo $sep;
              echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->labels->name, $position );
              if ( $show_current ) echo $sep . $before . get_the_title() . $after;
              elseif ( $show_last_sep ) echo $sep;
            } else {
              $cat = get_the_category(); $catID = $cat[0]->cat_ID;
              $parents = get_ancestors( $catID, 'category' );
              $parents = array_reverse( $parents );
              $parents[] = $catID;
              foreach ( $parents as $cat ) {
                $position += 1;
                if ( $position > 1 ) echo $sep;
                echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
              }
              if ( get_query_var( 'cpage' ) ) {
                $position += 1;
                echo $sep . sprintf( $link, get_permalink(), get_the_title(), $position );
                echo $sep . $before . sprintf( $text['cpage'], get_query_var( 'cpage' ) ) . $after;
              } else {
                if ( $show_current ) echo $sep . $before . get_the_title() . $after;
                elseif ( $show_last_sep ) echo $sep;
              }
            }

          } elseif ( is_post_type_archive() ) {
            $post_type = get_post_type_object( get_post_type() );
            if ( get_query_var( 'paged' ) ) {
              $position += 1;
              if ( $position > 1 ) echo $sep;
              echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->label, $position );
              echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
              if ( $show_home_link && $show_current ) echo $sep;
              if ( $show_current ) echo $before . $post_type->label . $after;
              elseif ( $show_home_link && $show_last_sep ) echo $sep;
            }

          } elseif ( is_attachment() ) {
            $parent = get_post( $parent_id );
            $cat = get_the_category( $parent->ID ); $catID = $cat[0]->cat_ID;
            $parents = get_ancestors( $catID, 'category' );
            $parents = array_reverse( $parents );
            $parents[] = $catID;
            foreach ( $parents as $cat ) {
              $position += 1;
              if ( $position > 1 ) echo $sep;
              echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
            }
            $position += 1;
            echo $sep . sprintf( $link, get_permalink( $parent ), $parent->post_title, $position );
            if ( $show_current ) echo $sep . $before . get_the_title() . $after;
            elseif ( $show_last_sep ) echo $sep;

          } elseif ( is_page() && ! $parent_id ) {
            if ( $show_home_link && $show_current ) echo $sep;
            if ( $show_current ) echo $before . get_the_title() . $after;
            elseif ( $show_home_link && $show_last_sep ) echo $sep;

          } elseif ( is_page() && $parent_id ) {
            $parents = get_post_ancestors( get_the_ID() );
            foreach ( array_reverse( $parents ) as $pageID ) {
              $position += 1;
              if ( $position > 1 ) echo $sep;
              echo sprintf( $link, get_page_link( $pageID ), get_the_title( $pageID ), $position );
            }
            if ( $show_current ) echo $sep . $before . get_the_title() . $after;
            elseif ( $show_last_sep ) echo $sep;

          } elseif ( is_tag() ) {
            if ( get_query_var( 'paged' ) ) {
              $position += 1;
              $tagID = get_query_var( 'tag_id' );
              echo $sep . sprintf( $link, get_tag_link( $tagID ), single_tag_title( '', false ), $position );
              echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
              if ( $show_home_link && $show_current ) echo $sep;
              if ( $show_current ) echo $before . sprintf( $text['tag'], single_tag_title( '', false ) ) . $after;
              elseif ( $show_home_link && $show_last_sep ) echo $sep;
            }

          } elseif ( is_author() ) {
            $author = get_userdata( get_query_var( 'author' ) );
            if ( get_query_var( 'paged' ) ) {
              $position += 1;
              echo $sep . sprintf( $link, get_author_posts_url( $author->ID ), sprintf( $text['author'], $author->display_name ), $position );
              echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
              if ( $show_home_link && $show_current ) echo $sep;
              if ( $show_current ) echo $before . sprintf( $text['author'], $author->display_name ) . $after;
              elseif ( $show_home_link && $show_last_sep ) echo $sep;
            }

          } elseif ( is_404() ) {
            if ( $show_home_link && $show_current ) echo $sep;
            if ( $show_current ) echo $before . $text['404'] . $after;
            elseif ( $show_last_sep ) echo $sep;

          } elseif ( has_post_format() && ! is_singular() ) {
            if ( $show_home_link && $show_current ) echo $sep;
            echo get_post_format_string( get_post_format() );
          }

          echo $wrap_after;

        }
      } // end of dimox_breadcrumbs()
}
