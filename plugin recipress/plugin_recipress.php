<?php
/*
Plugin Name: Recipress
Plugin URL: http://schematize.com.br
Description: Plugin de SEO para sites de receitas.
Version: 1.0.0
Author: Schematize
Author URL: http://schematize.com.br
*/

// Cria o tipo de post personalizado "Receita".
function create_recipe_post_type() {
    register_post_type( 'recipe',
        array(
            'labels' => array(
                'name' => __( 'Receitas' ),
                'singular_name' => __( 'Receita' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array( 'title', 'editor', 'excerpt'),
        )
    );
}
add_action( 'init', 'create_recipe_post_type' );

// Inclui o arquivo com o código para as caixas de meta.
require_once plugin_dir_path( __FILE__ ) . '/meta-boxes.php';

/*function print_recipe_schema_markup() {
    global $post;

    // Verifique se estamos em uma postagem de receita.
    if (is_singular('recipe')) {
        // Recupere os passos e ingredientes da postagem.
        $steps = get_post_meta($post->ID, '_steps', true);
        $ingredients = get_post_meta($post->ID, '_ingredients', true);
        $summary = get_post_meta($post->ID, 'recipe-summary', true);
        $cookTime = get_post_meta($post->ID, '_preparation_time', true);
        $recipeName = get_post_meta($post->ID, '_recipe_name', true);
        $calories = get_post_meta($post->ID, '_calories', true);
        $fatContent = get_post_meta($post->ID, '_fat_content', true);
        $image = get_post_meta($post->ID, '_image_url', true);

        // Comece a criar a estrutura de dados.
        $recipe_data = array(
            '@context' => 'https://schema.org/',
            '@type' => 'Recipe',
            'name' => get_the_title($post->ID),
            'author' => get_the_author_meta('display_name', $post->post_author),
            'datePublished' => get_the_date('c', $post->ID),
            'description' => wp_strip_all_tags(apply_filters('the_excerpt', $post->post_excerpt)),
            'recipeIngredient' => $ingredients,
            'recipeInstructions' => $steps,
            'recipeSummary' => $summary,
            'cookTime' => $cookTime,
            'name' => $recipeName,
            'nutrition' => array(
                '@type' => 'NutritionInformation',
                'calories' => $calories . ' calories',
                'fatContent' => $fatContent . ' grams fat'
            ),
            'image' => $image
        );

        // Imprima os dados na página como JSON.
        echo '<script type="application/ld+json">' . json_encode($recipe_data) . '</script>';
    }
}
add_action('wp_head', 'print_recipe_schema_markup');*/