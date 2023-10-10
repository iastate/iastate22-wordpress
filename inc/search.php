<?php

// Registration of starts_with variable
add_filter('query_vars', 'add_my_var');
function add_my_var($public_query_vars) {
    $public_query_vars[] = 'search_letter';
    return $public_query_vars;
}

add_filter( 'rest_event_query', function( $args ) {

    $ignore = array('page', 'per_page', 'search', 'order', 'orderby', 'slug');

    foreach ( $_GET as $key => $value ) {
      if (!in_array($key, $ignore)) {
        $args['meta_query'][] = array(
          'key'   => $key,
          'value' => $value,
        );
      }
    }

    return $args;
  });

// ACF Search solution

/**
 * [list_searcheable_acf list all the custom fields we want to include in our search query]
 * @return [array] [list of custom fields]
 */
function list_searcheable_acf(){
	return array("title", "sub_title", "excerpt_short", "excerpt_long", "first_name", "last_name", "location");
}


/**
 * [advanced_custom_search search that encompasses ACF/advanced custom fields and taxonomies and split expression before request]
 * @param  [query-part/string]      $where    [the initial "where" part of the search query]
 * @param  [object]                 $wp_query []
 * @return [query-part/string]      $where    [the "where" part of the search query as we customized]
 * see https://vzurczak.wordpress.com/2013/06/15/extend-the-default-wordpress-search/
 * credits to Vincent Zurczak for the base query structure/spliting tags section
 */
// INCOMPATABLE WITH WP-JSON

function advanced_custom_search( $where, $wp_query ) {

    global $wpdb;
    $prefix = $wpdb->prefix;

    if ( empty( $where ))
        return $where;

    // get search expression
    $terms = $wp_query->query_vars[ 's' ];

    // explode search expression to get search terms
    $exploded = explode( ' ', $terms );
    if( $exploded === FALSE || count( $exploded ) == 0 )
        $exploded = array( 0 => $terms );

    array_walk($exploded,function (&$value){
      $value = esc_sql($value);
    });

    // reset search in order to rebuild it as we wish
    $where = '';

    // get searchable_acf, a list of advanced custom fields you want to search content in
    $list_searcheable_acf = list_searcheable_acf();

    foreach( $exploded as $tag ) :
        $where .= "
          AND (
            (".$prefix."posts.post_title LIKE '%$tag%')
            OR (".$prefix."posts.post_content LIKE '%$tag%')
            OR EXISTS (
              SELECT * FROM ".$prefix."postmeta
	              WHERE post_id = ".$prefix."posts.ID
	                AND (";

        foreach ($list_searcheable_acf as $searcheable_acf) :
          if ($searcheable_acf == $list_searcheable_acf[0]):
            $where .= " (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
          else :
            $where .= " OR (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
          endif;
        endforeach;

	        $where .= ")
            )
            OR EXISTS (
              SELECT * FROM ".$prefix."comments
              WHERE comment_post_ID = ".$prefix."posts.ID
                AND comment_content LIKE '%$tag%'
            )
            OR EXISTS (
              SELECT * FROM ".$prefix."terms
              INNER JOIN ".$prefix."term_taxonomy
                ON ".$prefix."term_taxonomy.term_id = ".$prefix."terms.term_id
              INNER JOIN ".$prefix."term_relationships
                ON ".$prefix."term_relationships.term_taxonomy_id = ".$prefix."term_taxonomy.term_taxonomy_id
              WHERE (
          		taxonomy = 'post_tag'
            		OR taxonomy = 'category'
            		OR taxonomy = 'myCustomTax'
          		)
              	AND object_id = ".$prefix."posts.ID
              	AND ".$prefix."terms.name LIKE '%$tag%'
            )
        )";
    endforeach;
    return $where;
}

add_filter( 'posts_search', 'advanced_custom_search', 500, 2 );
