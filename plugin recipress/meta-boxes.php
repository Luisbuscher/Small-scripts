<?php

// Adiciona uma ação para adicionar a caixa de meta ao carregar a página de edição de post.
add_action('add_meta_boxes', 'add_steps_meta_box');

// Função para adicionar a caixa de meta de etapas.
function add_steps_meta_box()
{
  // Adiciona a caixa de meta de Passo a Passo.
  add_meta_box(
    'steps_meta_box', // ID da caixa de meta
    'Passo a Passo', // Título da caixa de meta
    'render_steps_meta_box', // Callback da função que renderiza o conteúdo da caixa de meta
    'recipe', // Tipo de post ao qual a caixa de meta será adicionada
    'normal', // Localização da caixa de meta
    'default' // Prioridade da caixa de meta
  );

  // Adiciona a caixa de meta de Ingredientes.
  add_meta_box(
    'ingredients_meta_box', // ID da caixa de meta
    'Ingredientes', // Título da caixa de meta
    'render_ingredients_meta_box', // Callback da função que renderiza o conteúdo da caixa de meta
    'recipe', // Tipo de post ao qual a caixa de meta será adicionada
    'normal', // Localização da caixa de meta
    'default' // Prioridade da caixa de meta
  );
}

?>
<style>
.steps,
.ingredients {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.step,
.ingredient {
  display: flex;
  align-items: center;
  gap: 10px;
}

.step-content,
.ingredient-content {
  display: flex;
  flex-grow: 1;
}

.step-textarea,
.ingredient-textarea {
  flex-grow: 1;
  width: 100%;
  min-width: 0;
}

.add-step,
.add-ingredient,
.remove-step,
.remove-ingredient {
  flex-shrink: 0;
}
</style>
<?php

// Função para renderizar o conteúdo da caixa de meta de etapas.
function render_steps_meta_box($post)
{
  // Adiciona um nonce field para verificar a autenticidade do pedido.
  wp_nonce_field('steps_meta_box', 'steps_meta_box_nonce');

  // Recupera os dados salvos anteriormente.
  $steps = get_post_meta($post->ID, '_steps', true);

  // Renderiza o campo para cada etapa.
  echo '<h4>Etapas</h4>';
  echo '<div class="steps">';
  if (!empty($steps)) {
    foreach ($steps as $index => $step) {
      echo '<div class="step step-row">';
      echo '<label for="step-' . esc_attr($index) . '">Etapa ' . esc_html($index + 1) . '</label>';
      echo '<div class="step-content step-row">';
      echo '<textarea id="step-' . esc_attr($index) . '" name="steps[]" rows="1" style="resize: none;" class="step-textarea">' . esc_textarea($step) . '</textarea>';
      echo '<button type="button" class="add-step">+</button>';
      echo '<button type="button" class="remove-step">-</button>';
      echo '</div>';
      echo '</div>';
    }
  } else {
    echo '<div class="step step-row">';
    echo '<label for="step-0">Etapa 1</label>';
    echo '<div class="step-content step-row">';
    echo '<textarea id="step-0" name="steps[]" rows="1" style="resize: none;" class="step-textarea"></textarea>';
    echo '<button type="button" class="add-step">+</button>';
    echo '<button type="button" class="remove-step">-</button>';
    echo '</div>';
    echo '</div>';
  }
  echo '</div>';

  // Adiciona o script para adicionar e remover etapas dinamicamente.
?>
  <script>
    jQuery(document).ready(function($) {
      // Função para adicionar uma nova etapa
      function addStep() {
        var stepCount = $('.step').length;
        var newStep = $('<div class="step">');
        newStep.append('<label for="step-' + stepCount + '">Etapa ' + (stepCount + 1) + '</label>');
        newStep.append('<div class="step-content">');
        newStep.append('<textarea id="step-' + stepCount + '" name="steps[]" rows="1" style="resize: none;" class="step-textarea"></textarea>');
        newStep.append('<button type="button" class="add-step">+</button>');
        newStep.append('<button type="button" class="remove-step">-</button>');
        newStep.append('</div>');
        newStep.find('.add-step').on('click', addStep); // Adiciona o evento de clique ao botão de adicionar da nova etapa
        newStep.find('.remove-step').on('click', removeStep); // Adiciona o evento de clique ao botão de remover da nova etapa
        $('.steps').append(newStep);
        newStep.find('.step-textarea').focus();
      }

      function removeStep() {
        $(this).closest('.step').remove();
        if ($('.step').length === 0) {
          addStep();
        }
      }

      $('.add-step').on('click', addStep); // Adiciona o evento de clique ao botão de adicionar inicial
      $(document).on('click', '.remove-step', removeStep); // Adiciona o evento de clique aos botões de remover existentes

      // Evento de tecla pressionada nos campos de texto de passo a passo.
      $(document).on('keypress', '.step-textarea', function(e) {
        if (e.which === 13) {
          e.preventDefault();
          addStep();
        }
      });

      // Evento de "colar" no campo de texto da primeira etapa.
      $(document).on('paste', '#step-0', function(e) {
        // Obtém o texto colado.
        var pastedText = e.originalEvent.clipboardData.getData('text');

        // Divide o texto em linhas.
        var lines = pastedText.split('\n');

        // Limpa todos os campos de texto de etapas.
        $('.step').remove();

        // Adiciona os passos restantes da lista aos campos de texto da div "Etapas".
        for (var i = 0; i < lines.length; i++) {
          var stepCount = $('.step').length;
          var newStep = $('<div class="step">');
          newStep.append('<label for="step-' + stepCount + '">Etapa ' + (stepCount + 1) + '</label>');
          newStep.append('<div class="step-content">');
          newStep.append('<textarea id="step-' + stepCount + '" name="steps[]" rows="1" style="resize: none;" class="step-textarea">' + lines[i] + '</textarea>');
          newStep.append('<button type="button" class="add-step">+</button>');
          newStep.append('<button type="button" class="remove-step">-</button>');
          newStep.append('</div>');
          newStep.find('.add-step').on('click', addStep);
          newStep.find('.remove-step').on('click', removeStep);
          $('.steps').append(newStep);
        }
      });
    });
  </script>
<?php
}

// Função para renderizar o conteúdo da caixa de meta de ingredientes.
function render_ingredients_meta_box($post)
{
  // Adiciona um nonce field para verificar a autenticidade do pedido.
  wp_nonce_field('ingredients_meta_box', 'ingredients_meta_box_nonce');

  // Recupera os dados salvos anteriormente.
  $ingredients = get_post_meta($post->ID, '_ingredients', true);

  // Renderiza o campo para cada ingrediente.
  echo '<h4>Ingredientes</h4>';
  echo '<div class="ingredients">';
  if (!empty($ingredients)) {
    foreach ($ingredients as $index => $ingredient) {
      echo '<div class="ingredient ingredient-row">';
      echo '<label for="ingredient-' . esc_attr($index) . '">Ingrediente ' . esc_html($index + 1) . '</label>';
      echo '<div class="ingredient-content ingredient-row">';
      echo '<textarea id="ingredient-' . esc_attr($index) . '" name="ingredients[]" rows="1" style="resize: none;" class="ingredient-textarea">' . esc_textarea($ingredient) . '</textarea>';
      echo '<button type="button" class="add-ingredient">+</button>';
      echo '<button type="button" class="remove-ingredient">-</button>';
      echo '</div>';
      echo '</div>';
    }
  } else {
    echo '<div class="ingredient ingredient-row">';
    echo '<label for="ingredient-0">Ingrediente 1</label>';
    echo '<div class="ingredient-content ingredient-row">';
    echo '<textarea id="ingredient-0" name="ingredients[]" rows="1" style="resize: none;" class="ingredient-textarea"></textarea>';
    echo '<button type="button" class="add-ingredient">+</button>';
    echo '<button type="button" class="remove-ingredient">-</button>';
    echo '</div>';
    echo '</div>';
  }
  echo '</div>';

  // Adiciona o script para adicionar e remover ingredientes dinamicamente.
?>
  <script>
    jQuery(document).ready(function($) {
      // Função para adicionar um novo ingrediente.
      function addIngredient() {
        var ingredientCount = $('.ingredient').length;
        var newIngredient = $('<div class="ingredient">');
        newIngredient.append('<label for="ingredient-' + ingredientCount + '">Ingrediente ' + (ingredientCount + 1) + '</label>');
        newIngredient.append('<div class="ingredient-content">');
        newIngredient.append('<textarea id="ingredient-' + ingredientCount + '" name="ingredients[]" rows="1" style="resize: none;" class="ingredient-textarea"></textarea>');
        newIngredient.append('<button type="button" class="add-ingredient">+</button>');
        newIngredient.append('<button type="button" class="remove-ingredient">-</button>');
        newIngredient.append('</div>');
        newIngredient.find('.add-ingredient').on('click', addIngredient); // Adiciona o evento de clique ao botão de adicionar do novo ingrediente.
        newIngredient.find('.remove-ingredient').on('click', removeIngredient); // Adiciona o evento de clique ao botão de remover do novo ingrediente.
        $('.ingredients').append(newIngredient);
        newIngredient.find('.ingredient-textarea').focus();
      }

      function removeIngredient() {
        $(this).closest('.ingredient').remove();
        if ($('.ingredient').length === 0) {
          addIngredient();
        }
      }

      $(document).on('click', '.add-ingredient', addIngredient); // Adiciona o evento de clique ao botão de adicionar inicial
      $(document).on('click', '.remove-ingredient', removeIngredient); // Adiciona o evento de clique aos botões de remover existentes

      // Evento de tecla pressionada nos campos de texto de ingredientes.
      $(document).on('keypress', '.ingredient-textarea', function(e) {
        if (e.which === 13) {
          e.preventDefault();
          addIngredient();
        }
      });

      // Evento de "colar" no campo de texto do primeiro ingrediente.
      $(document).on('paste', '#ingredient-0', function(e) {
        // Obtém o texto colado.
        var pastedText = e.originalEvent.clipboardData.getData('text');

        // Divide o texto em linhas.
        var lines = pastedText.split('\n');

        // Limpa todos os campos de texto de ingredientes.
        $('.ingredient').remove();

        // Adiciona os ingredientes restantes da lista aos campos de texto da div "Ingredientes".
        for (var i = 0; i < lines.length; i++) {
          var ingredientCount = $('.ingredient').length;
          var newIngredient = $('<div class="ingredient">');
          newIngredient.append('<label for="ingredient-' + ingredientCount + '">Ingrediente ' + (ingredientCount + 1) + '</label>');
          newIngredient.append('<div class="ingredient-content">');
          newIngredient.append('<textarea id="ingredient-' + ingredientCount + '" name="ingredients[]" rows="1" style="resize: none;" class="ingredient-textarea">' + lines[i] + '</textarea>');
          newIngredient.append('<button type="button" class="add-ingredient">+</button>');
          newIngredient.append('<button type="button" class="remove-ingredient">-</button>');
          newIngredient.append('</div>');
          newIngredient.find('.add-ingredient').on('click', addIngredient);
          newIngredient.find('.remove-ingredient').on('click', removeIngredient);
          $('.ingredients').append(newIngredient);
        }
      });
    });
  </script>
<?php
}


// Adiciona uma ação para salvar os dados da caixa de meta ao salvar o post.
add_action('save_post', 'save_steps_meta_box_data');

// Função para salvar os dados da caixa de meta de etapas e ingredientes.
function save_steps_meta_box_data($post_id)
{
  // Verifica se o nonce é válido.
  if (!isset($_POST['steps_meta_box_nonce']) || !wp_verify_nonce($_POST['steps_meta_box_nonce'], 'steps_meta_box')) {
    return;
  }

  // Verifica se o usuário tem permissão para editar o post.
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  // Verifica se os dados foram enviados e atualiza os dados no banco de dados.
  if (isset($_POST['steps'])) {
    update_post_meta($post_id, '_steps', array_map('sanitize_text_field', $_POST['steps']));
  } else {
    delete_post_meta($post_id, '_steps');
  }

  if (isset($_POST['ingredients'])) {
    update_post_meta($post_id, '_ingredients', array_map('sanitize_text_field', $_POST['ingredients']));
  } else {
    delete_post_meta($post_id, '_ingredients');
  }
}

// Adiciona uma ação para adicionar a caixa de meta ao carregar a página de edição de post.
add_action('add_meta_boxes', 'add_recipe_details_meta_box');

// Função para adicionar a caixa de meta de detalhes da receita.
function add_recipe_details_meta_box()
{
  // Adiciona a caixa de meta.
  add_meta_box(
    'recipe_details_meta_box', // ID da caixa de meta
    'Detalhes Receitas', // Título da caixa de meta
    'render_recipe_details_meta_box', // Callback da função que renderiza o conteúdo da caixa de meta
    'recipe', // Tipo de post ao qual a caixa de meta será adicionada
    'normal', // Localização da caixa de meta
    'default' // Prioridade da caixa de meta
  );
}

// Função para renderizar o conteúdo da caixa de meta de detalhes da receita.
function render_recipe_details_meta_box($post)
{
  wp_nonce_field('recipe_details_meta_box', 'recipe_details_meta_box_nonce');

  $cuisine = get_post_meta($post->ID, '_cuisine', true);
  $course = get_post_meta($post->ID, '_course', true);

  $cuisine_options = array('Brasileira', 'Asiática', 'Espanha', 'Estados Unidos', 'Francesa', 'Grega', 'Italiana', 'Oriente Médio');
  $course_options = array('Acompanhamento', 'Bolos', 'Mousse', 'Pães', 'Peixes', 'Prato Principal', 'Sobremesa', 'Biscoito');

  echo '<div class="recipe-details" style="display: flex; align-items: center;">';
  echo '<div class="column">';
  echo '<h4>Cozinha</h4>';
  foreach ($cuisine_options as $option) {
    $option_value = esc_attr($option);
    echo '<div class="uk-margin">';
    echo '<div class="uk-form-controls">';
    echo '<label><input class="uk-checkbox" type="checkbox" name="cuisine[]" value="' . $option_value . '"' . (is_array($cuisine) && in_array($option, $cuisine) ? ' checked' : '') . '> ' . esc_html($option) . '</label>';
    echo '</div>';
    echo '</div>';
  }
  echo '</div>';
  echo '<div class="column">';
  echo '<h4>Prato</h4>';
  foreach ($course_options as $option) {
    $option_value = esc_attr($option);
    echo '<div class="uk-margin">';
    echo '<div class="uk-form-controls">';
    echo '<label><input class="uk-checkbox" type="checkbox" name="course[]" value="' . $option_value . '"' . (is_array($course) && in_array($option, $course) ? ' checked' : '') . '> ' . esc_html($option) . '</label>';
    echo '</div>';
    echo '</div>';
  }
  echo '</div>';
  echo '</div>';

  echo '<style>';
  echo '.recipe-details { display: flex; align-items: center; }';
  echo '.column { flex: 1; }';
  echo '</style>';
}

// Adiciona uma ação para salvar os dados da caixa de meta ao salvar o post.
add_action('save_post', 'save_recipe_details_meta_box_data');

// Função para salvar os dados da caixa de meta de detalhes da receita.
function save_recipe_details_meta_box_data($post_id)
{
  // Verifica se o nonce é válido.
  if (!isset($_POST['recipe_details_meta_box_nonce']) || !wp_verify_nonce($_POST['recipe_details_meta_box_nonce'], 'recipe_details_meta_box')) {
    return;
  }

  // Verifica se o usuário tem permissão para editar o post.
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  // Verifica se os dados foram enviados e atualiza os dados no banco de dados.
  if (isset($_POST['cuisine'])) {
    update_post_meta($post_id, '_cuisine', $_POST['cuisine']);
  } else {
    delete_post_meta($post_id, '_cuisine');
  }

  if (isset($_POST['course'])) {
    update_post_meta($post_id, '_course', $_POST['course']);
  } else {
    delete_post_meta($post_id, '_course');
  }
}

// Adiciona uma ação para adicionar a caixa de meta ao carregar a página de edição de post.
add_action('add_meta_boxes', 'add_recipe_info_meta_box');

// Função para adicionar a caixa de meta de informações da receita.
function add_recipe_info_meta_box()
{
  // Adiciona a caixa de meta.
  add_meta_box(
    'recipe_info_meta_box', // ID da caixa de meta
    'Informações da Receita', // Título da caixa de meta
    'render_recipe_info_meta_box', // Callback da função que renderiza o conteúdo da caixa de meta
    'recipe', // Tipo de post ao qual a caixa de meta será adicionada
    'normal', // Localização da caixa de meta
    'default' // Prioridade da caixa de meta
  );
}

// Função para renderizar o conteúdo da caixa de meta de informações da receita.
function render_recipe_info_meta_box($post)
{
  wp_nonce_field('recipe_info_meta_box', 'recipe_info_meta_box_nonce');

  // Recupera os valores dos campos salvos anteriormente
  $tempo_cozinhamento = get_post_meta($post->ID, 'tempo_cozinhamento', true);
  $tempo_preparacao = get_post_meta($post->ID, 'tempo_preparacao', true);
  $tempo_execucao = get_post_meta($post->ID, 'tempo_execucao', true);
  $tempo_total = get_post_meta($post->ID, 'tempo_total', true);
  
  echo '<table>';
  echo '<tr>';
  echo '<td>';
  echo '<label for="tempo_cozinhamento">Tempo de Cozinhamento:</label>';
  echo '</td>';
  echo '<td>';
  echo '<input type="number" name="tempo_cozinhamento" id="tempo_cozinhamento" value="' . esc_attr($tempo_cozinhamento) . '" style="width: 50px;" />';
  echo '<select name="tempo_cozinhamento_unidade">';
  echo '<option value="minuto(s)"' . selected(get_post_meta($post->ID, 'tempo_cozinhamento_unidade', true), 'minuto(s)', false) . '>minuto(s)</option>';
  echo '<option value="hora(s)"' . selected(get_post_meta($post->ID, 'tempo_cozinhamento_unidade', true), 'hora(s)', false) . '>hora(s)</option>';
  echo '</select>';
  echo '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<td>';
  echo '<label for="tempo_preparacao">Tempo de Preparação:</label>';
  echo '</td>';
  echo '<td>';
  echo '<input type="number" name="tempo_preparacao" id="tempo_preparacao" value="' . esc_attr($tempo_preparacao) . '" style="width: 50px;" />';
  echo '<select name="tempo_preparacao_unidade">';
  echo '<option value="minuto(s)"' . selected(get_post_meta($post->ID, 'tempo_preparacao_unidade', true), 'minuto(s)', false) . '>minuto(s)</option>';
  echo '<option value="hora(s)"' . selected(get_post_meta($post->ID, 'tempo_preparacao_unidade', true), 'hora(s)', false) . '>hora(s)</option>';
  echo '</select>';
  echo '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<td>';
  echo '<label for="tempo_execucao">Tempo de Execução:</label>';
  echo '</td>';
  echo '<td>';
  echo '<input type="number" name="tempo_execucao" id="tempo_execucao" value="' . esc_attr($tempo_execucao) . '" style="width: 50px;" />';
  echo '<select name="tempo_execucao_unidade">';
  echo '<option value="minuto(s)"' . selected(get_post_meta($post->ID, 'tempo_execucao_unidade', true), 'minuto(s)', false) . '>minuto(s)</option>';
  echo '<option value="hora(s)"' . selected(get_post_meta($post->ID, 'tempo_execucao_unidade', true), 'hora(s)', false) . '>hora(s)</option>';
  echo '</select>';
  echo '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<td>';
  echo '<label for="tempo_total">Tempo Total:</label>';
  echo '</td>';
  echo '<td>';
  echo '<input type="number" name="tempo_total" id="tempo_total" value="' . esc_attr($tempo_total) . '" style="width: 50px;" />';
  echo '<select name="tempo_total_unidade">';
  echo '<option value="minuto(s)"' . selected(get_post_meta($post->ID, 'tempo_total_unidade', true), 'minuto(s)', false) . '>minuto(s)</option>';
  echo '<option value="hora(s)"' . selected(get_post_meta($post->ID, 'tempo_total_unidade', true), 'hora(s)', false) . '>hora(s)</option>';
  echo '</select>';
  echo '</td>';
  echo '</tr>';
  echo '</table>';
}


// Adiciona uma ação para salvar os dados da caixa de meta ao salvar o post.
add_action('save_post', 'save_recipe_info_meta_box_data');

// Função para salvar os dados da caixa de meta de informações da receita.
function save_recipe_info_meta_box_data($post_id)
{
  // Verifica se o nonce é válido.
  if (!isset($_POST['recipe_info_meta_box_nonce']) || !wp_verify_nonce($_POST['recipe_info_meta_box_nonce'], 'recipe_info_meta_box')) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
}

// Verifica se os campos foram enviados
if (isset($_POST['tempo_cozinhamento'], $_POST['tempo_preparacao'], $_POST['tempo_execucao'], $_POST['tempo_total'])) {
    // Salva os valores dos campos
    update_post_meta($post_id, 'tempo_cozinhamento', sanitize_text_field($_POST['tempo_cozinhamento']));
    update_post_meta($post_id, 'tempo_cozinhamento_unidade', sanitize_text_field($_POST['tempo_cozinhamento_unidade']));
    update_post_meta($post_id, 'tempo_preparacao', sanitize_text_field($_POST['tempo_preparacao']));
    update_post_meta($post_id, 'tempo_preparacao_unidade', sanitize_text_field($_POST['tempo_preparacao_unidade']));
    update_post_meta($post_id, 'tempo_execucao', sanitize_text_field($_POST['tempo_execucao']));
    update_post_meta($post_id, 'tempo_execucao_unidade', sanitize_text_field($_POST['tempo_execucao_unidade']));
    update_post_meta($post_id, 'tempo_total', sanitize_text_field($_POST['tempo_total']));
    update_post_meta($post_id, 'tempo_total_unidade', sanitize_text_field($_POST['tempo_total_unidade']));
}
}

// Adiciona uma ação para adicionar a caixa de meta ao carregar a página de edição de post.
add_action('add_meta_boxes', 'add_nutritional_values_meta_box');

// Função para adicionar a caixa de meta de valores nutricionais.
function add_nutritional_values_meta_box()
{
  // Adiciona a caixa de meta.
  add_meta_box(
    'nutritional_values_meta_box', // ID da caixa de meta
    'Valores Nutricionais', // Título da caixa de meta
    'render_nutritional_values_meta_box', // Callback da função que renderiza o conteúdo da caixa de meta
    'recipe', // Tipo de post ao qual a caixa de meta será adicionada
    'normal', // Localização da caixa de meta
    'default' // Prioridade da caixa de meta
  );
}

// Função para renderizar o conteúdo da caixa de meta de valores nutricionais.
function render_nutritional_values_meta_box($post)
{
  wp_nonce_field('nutritional_values_meta_box', 'nutritional_values_meta_box_nonce');

  $preparation_time = get_post_meta($post->ID, '_preparation_time', true);
  $recipe_name = get_post_meta($post->ID, '_recipe_name', true);
  $calories = get_post_meta($post->ID, '_calories', true);
  $calories = isset($calories) ? $calories : "0";
  $cholesterol = get_post_meta($post->ID, '_cholesterol', true);
  $cholesterol = isset($cholesterol) ? $cholesterol : "0";
  $fat = get_post_meta($post->ID, '_fat', true);
  $fat = isset($fat) ? $fat : "0";
  $saturated_fat = get_post_meta($post->ID, '_saturated_fat', true);
  $saturated_fat = isset($saturated_fat) ? $saturated_fat : "0";
  $unsaturated_fat = get_post_meta($post->ID, '_unsaturated_fat', true);
  $unsaturated_fat = isset($unsaturated_fat) ? $unsaturated_fat : "0";
  $trans_fat = get_post_meta($post->ID, '_trans_fat', true);
  $trans_fat = isset($trans_fat) ? $trans_fat : "0";
  $carbohydrate = get_post_meta($post->ID, '_carbohydrate', true);
  $carbohydrate = isset($carbohydrate) ? $carbohydrate : "0";
  $protein = get_post_meta($post->ID, '_protein', true);
  $protein = isset($protein) ? $protein : "0";
  $fiber = get_post_meta($post->ID, '_fiber', true);
  $fiber = isset($fiber) ? $fiber : "0";
  $sodium = get_post_meta($post->ID, '_sodium', true);
  $sodium = isset($sodium) ? $sodium : "0";
  $sugar = get_post_meta($post->ID, '_sugar', true);
  $sugar = isset($sugar) ? $sugar : "0";

  $preparation_time_value = preg_replace('/[^0-9]/', '', $preparation_time);
  $preparation_time_unit = preg_replace('/[^HM]/', '', strtoupper($preparation_time));

  echo '<div class="meta-box">';
  echo '<div class="field">';
  echo '<label for="preparation-time">Tempo de Preparo</label>';
  echo '<input type="text" id="preparation-time" name="preparation_time" value="' . esc_attr($preparation_time_value) . '">';
  echo '<select id="preparation-time-unit" name="preparation_time_unit">';
  echo '<option value="H"' . selected($preparation_time_unit, 'H', false) . '>Hora(s)</option>';
  echo '<option value="M"' . selected($preparation_time_unit, 'M', false) . '>Minuto(s)</option>';
  echo '</select>';
  echo '</div>';

  echo '<div class="field">';
  echo '<label for="recipe-name">Nome da Receita</label>';
  echo '<input type="text" id="recipe-name" name="recipe_name" value="' . esc_attr($recipe_name) . '">';
  echo '</div>';

  echo '<div class="field">';
  echo '<label for="calories">Calorias</label>';
  echo '<input type="number" id="calories" name="calories" value="' . esc_attr($calories) . '">';
  echo '</div>';

  echo '<div class="field">';
  echo '<label for="cholesterol">Colesterol</label>';
  echo '<input type="number" id="cholesterol" name="cholesterol" value="' . esc_attr($cholesterol) . '">';
  echo '</div>';

  echo '<div class="field">';
  echo '<label for="fat">Gordura</label>';
  echo '<input type="number" id="fat" name="fat" value="' . esc_attr($fat) . '">';
  echo '</div>';

  echo '<div class="field">';
  echo '<label for="saturated-fat">Gordura Saturada</label>';
  echo '<input type="number" id="saturated-fat" name="saturated_fat" value="' . esc_attr($saturated_fat) . '">';
  echo '</div>';

  echo '<div class="field">';
  echo '<label for="unsaturated-fat">Gordura Insaturada</label>';
  echo '<input type="number" id="unsaturated-fat" name="unsaturated_fat" value="' . esc_attr($unsaturated_fat) . '">';
  echo '</div>';

  echo '<div class="field">';
  echo '<label for="trans-fat">Gordura Trans</label>';
  echo '<input type="number" id="trans-fat" name="trans_fat" value="' . esc_attr($trans_fat) . '">';
  echo '</div>';

  echo '<div class="field">';
  echo '<label for="carbohydrate">Carboidrato</label>';
  echo '<input type="number" id="carbohydrate" name="carbohydrate" value="' . esc_attr($carbohydrate) . '">';
  echo '</div>';

  echo '<div class="field">';
  echo '<label for="protein">Proteínas</label>';
  echo '<input type="number" id="protein" name="protein" value="' . esc_attr($protein) . '">';
  echo '</div>';

  echo '<div class="field">';
  echo '<label for="fiber">Fibra</label>';
  echo '<input type="number" id="fiber" name="fiber" value="' . esc_attr($fiber) . '">';
  echo '</div>';

  echo '<div class="field">';
  echo '<label for="sodium">Sódio</label>';
  echo '<input type="number" id="sodium" name="sodium" value="' . esc_attr($sodium) . '">';
  echo '</div>';

  echo '<div class="field">';
  echo '<label for="sugar">Açúcar</label>';
  echo '<input type="number" id="sugar" name="sugar" value="' . esc_attr($sugar) . '">';
  echo '</div>';
  echo '</div>';

  echo '<style>';
  echo '.meta-box { display: flex; flex-wrap: wrap; }';
  echo '.field { margin-right: 20px; margin-bottom: 10px; }';
  echo '.field label { display: block; }';
  echo '</style>';
}


// Adiciona uma ação para salvar os dados da caixa de meta ao salvar o post.
add_action('save_post', 'save_nutritional_values_meta_box_data');

// Função para salvar os dados da caixa de meta de valores nutricionais.
function save_nutritional_values_meta_box_data($post_id)
{
  // Verifica se o nonce é válido.
  if (!isset($_POST['recipe_info_meta_box_nonce']) || !wp_verify_nonce($_POST['recipe_info_meta_box_nonce'], 'recipe_info_meta_box')) {
    return;
  }

  // Verifica se o usuário tem permissão para editar o post.
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  // Verifica se os dados foram enviados e atualiza os dados no banco de dados.
  if (isset($_POST['preparation_time']) && isset($_POST['preparation_time_unit'])) {
    update_post_meta($post_id, '_preparation_time', 'PT' . sanitize_text_field($_POST['preparation_time'] . $_POST['preparation_time_unit']));
  }

  if (isset($_POST['recipe_name'])) {
    update_post_meta($post_id, '_recipe_name', sanitize_text_field($_POST['recipe_name']));
  }

  if (isset($_POST['calories'])) {
    update_post_meta($post_id, '_calories', sanitize_text_field($_POST['calories']));
  }

  if (isset($_POST['cholesterol'])) {
    update_post_meta($post_id, '_cholesterol', sanitize_text_field($_POST['cholesterol']));
  }

  if (isset($_POST['fat'])) {
    update_post_meta($post_id, '_fat', sanitize_text_field($_POST['fat']));
  }

  if (isset($_POST['saturated_fat'])) {
    update_post_meta($post_id, '_saturated_fat', sanitize_text_field($_POST['saturated_fat']));
  }

  if (isset($_POST['unsaturated_fat'])) {
    update_post_meta($post_id, '_unsaturated_fat', sanitize_text_field($_POST['unsaturated_fat']));
  }

  if (isset($_POST['trans_fat'])) {
    update_post_meta($post_id, '_trans_fat', sanitize_text_field($_POST['trans_fat']));
  }
  if (isset($_POST['carbohydrate'])) {
    update_post_meta($post_id, '_carbohydrate', sanitize_text_field($_POST['carbohydrate']));
  }

  if (isset($_POST['protein'])) {
    update_post_meta($post_id, '_protein', sanitize_text_field($_POST['protein']));
  }

  if (isset($_POST['fiber'])) {
    update_post_meta($post_id, '_fiber', sanitize_text_field($_POST['fiber']));
  }

  if (isset($_POST['sodium'])) {
    update_post_meta($post_id, '_sodium', sanitize_text_field($_POST['sodium']));
  }

  if (isset($_POST['sugar'])) {
    update_post_meta($post_id, '_sugar', sanitize_text_field($_POST['sugar']));
  }
}


// Adiciona uma ação para adicionar a caixa de meta ao carregar a página de edição de post.
add_action('add_meta_boxes', 'add_recipe_video_meta_box');

// Função para adicionar a caixa de meta de vídeo da receita.
function add_recipe_video_meta_box()
{
  // Adiciona a caixa de meta.
  add_meta_box(
    'recipe_video_meta_box', // ID da caixa de meta
    'Vídeo', // Título da caixa de meta
    'render_recipe_video_meta_box', // Callback da função que renderiza o conteúdo da caixa de meta
    'recipe', // Tipo de post ao qual a caixa de meta será adicionada
    'normal', // Localização da caixa de meta
    'default' // Prioridade da caixa de meta
  );
}

// Função para renderizar o conteúdo da caixa de meta de vídeo da receita.
function render_recipe_video_meta_box($post)
{
  // Adiciona um nonce field para verificar a autenticidade do pedido.
  wp_nonce_field('recipe_video_meta_box', 'recipe_video_meta_box_nonce');

  // Recupera os dados salvos anteriormente.
  $video_name = get_post_meta($post->ID, '_video_name', true);
  $video_url = get_post_meta($post->ID, '_video_url', true);
  $image_url = get_post_meta($post->ID, '_image_url', true);

  // Renderiza os campos para cada informação.
  echo '<div class="recipe-video">';
  echo '<div class="field">';
  echo '<label for="video-name">Nome do Vídeo</label>';
  echo '<input type="text" id="video-name" name="video_name" value="' . esc_attr($video_name) . '">';
  echo '</div>';
  echo '<div class="field">';
  echo '<label for="video-url">URL do Vídeo</label>';
  echo '<input type="text" id="video-url" name="video_url" value="' . esc_attr($video_url) . '">';
  echo '</div>';
  echo '<div class="field">';
  echo '<label for="image-url">Imagem destaque:</label>';
  echo '<input type="text" id="image-url" name="image_url" value="' . esc_attr($image_url) . '" readonly>';
  echo '<button class="upload-image-button button">Upload de Imagem</button>';
  echo '</div>';
  echo '</div>';
  echo '<style>';
  echo '.recipe-video { display: flex; flex-wrap: wrap; justify-content: center; }';
  echo '.field { margin-right: 20px; margin-bottom: 10px; }';
  echo '</style>';
?>
  <script>
    // Evento de clique no botão "Upload de Imagem".
    jQuery(document).on('click', '.upload-image-button', function(e) {
      e.preventDefault();

      var imageUploader = wp.media({
        title: 'Selecione uma Imagem',
        button: {
          text: 'Selecionar'
        },
        multiple: false
      });

      // Abre a janela de upload de imagem.
      imageUploader.open();

      // Quando uma imagem é selecionada.
      imageUploader.on('select', function() {
        var attachment = imageUploader.state().get('selection').first().toJSON();

        // Define o URL da imagem selecionada no campo de texto.
        jQuery('#image-url').val(attachment.url);
      });
    });
  </script>
<?php
}

// Adiciona uma ação para salvar os dados da caixa de meta ao salvar o post.
add_action('save_post', 'save_recipe_video_meta_box_data');

// Função para salvar os dados da caixa de meta de vídeo da receita.
function save_recipe_video_meta_box_data($post_id)
{
  // Verifica se o nonce é válido.
  if (!isset($_POST['recipe_video_meta_box_nonce']) || !wp_verify_nonce($_POST['recipe_video_meta_box_nonce'], 'recipe_video_meta_box')) {
    return;
  }

  // Verifica se o usuário tem permissão para editar o post.
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  // Verifica se os dados foram enviados e atualiza os dados no banco de dados.
  if (isset($_POST['video_name'])) {
    update_post_meta($post_id, '_video_name', sanitize_text_field($_POST['video_name']));
  }

  if (isset($_POST['video_url'])) {
    update_post_meta($post_id, '_video_url', esc_url_raw($_POST['video_url']));
  }
  if (isset($_POST['image_url'])) {
    update_post_meta($post_id, '_image_url', sanitize_text_field($_POST['image_url']));
  } else {
    delete_post_meta($post_id, '_image_url');
  }
}
