<?php echo $header; ?>


<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/fretereal.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_client_id; ?></td>
            <td><input type="text" name="fretereal_client_id" value="<?php echo $fretereal_client_id; ?>" />
              <?php if ($error_client_id) { ?>
              <span class="error"><?php echo $error_client_id; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_client_secret; ?></td>
            <td><input type="text" name="fretereal_client_secret" value="<?php echo $fretereal_client_secret; ?>" />
              <?php if ($error_client_secret) { ?>
              <span class="error"><?php echo $error_client_secret; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $fretesAceitos['label']; ?></td>
            <td id="service">
              <div id="US">
                <div class="scrollbox">
                    <?php
                    foreach ($fretesAceitos['value'] as $key => $value) {
                      echo '<div class="'.($key%2 ? "even" : "odd").'">';
                      echo '<input type="checkbox" name="fretereal_'.$value['value'].'" value="1" '. ($value['check'] ? 'checked="checked"' : "" ) .' />';
                      echo $value['label'];
                      echo '</div>';
                    }
                    ?>
                </div>
              </div>
              <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
          </tr>
          <tr>
            <td><?php echo $entry_own_hands; ?></td>
            <td><?php if ($fretereal_own_hands) { ?>
              <input type="radio" name="fretereal_own_hands" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="fretereal_own_hands" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="fretereal_own_hands" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="fretereal_own_hands" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_receive_alert; ?></td>
            <td><?php if ($fretereal_receive_alert) { ?>
              <input type="radio" name="fretereal_receive_alert" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="fretereal_receive_alert" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="fretereal_receive_alert" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="fretereal_receive_alert" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_send_values; ?></td>
            <td><?php if ($fretereal_send_values) { ?>
              <input type="radio" name="fretereal_send_values" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="fretereal_send_values" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="fretereal_send_values" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="fretereal_send_values" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_cobrar_caixas; ?></td>
            <td><?php if ($fretereal_cobrar_caixas) { ?>
              <input type="radio" name="fretereal_cobrar_caixas" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="fretereal_cobrar_caixas" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="fretereal_cobrar_caixas" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="fretereal_cobrar_caixas" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_extra_days; ?></td>
            <td><input type="text" name="fretereal_extra_days" value="<?php echo $fretereal_extra_days; ?>" size="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="fretereal_status">
                <?php if ($fretereal_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="fretereal_sort_order" value="<?php echo $fretereal_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'fretereal_origin\']').bind('change', function() {
	$('#service > div').hide();	
										 
	$('#' + this.value).show();	
});

$('select[name=\'fretereal_origin\']').trigger('change');
//--></script> 
<?php echo $footer; ?>