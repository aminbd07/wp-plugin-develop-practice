<?php
// if ($_POST) {
//     echo "<h3>Post Data</h3>";
//     var_dump($_POST);
// }
global $wpdb;
$table_name = $wpdb->prefix . 'student_list';
$students = $wpdb->get_results("SELECT * FROM {$table_name} ");
?>



<h2>Welcome </h2>
<p>Welcome to our plugin home </p>
<div>
    All Students :
    <div class='itemRow'>
        <?php
        foreach ($students as $student) {
            echo "<p > ID : {$student->id} :  {$student->name}</p>";
        }
        ?>
    </div>
</div>
<form id="myForm">
    <input type="hidden" name="action" value="mfp-save-my-data" />
    <label>Name
        <input type="text" name="name" value="" id="name" />
    </label>

    <label>Class
        <input type="text" name="class" value="" id="class" />
    </label>

    <label>Age
        <input type="text" name="age" value="" id="age" />
    </label>

    <input type="submit" name="submit" value="Save" />

</form>

<script>

    jQuery("#myForm").submit(function(e) {
        e.preventDefault();
        let data = {
            action: 'mfp-save-my-data',
            name: jQuery("#name").val(),
            class: jQuery("#class").val(),
            age: jQuery("#age").val(),
        };

        jQuery.post(mfpAjaxVar.ajaxurl, data,
            function(response) {
                if (response.success) {
                    let html = "<p> ID : " + response.id + "  : " + jQuery("#name").val() + "   </p>";
                    jQuery(".itemRow").append(html)
                }
            }
        );
    })
</script>