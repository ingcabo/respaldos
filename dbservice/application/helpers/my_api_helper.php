<?php
function remove_unknown_fields($raw_data, $expected_fields)
{
    $new_data = array();
    foreach ($raw_data as $field_name => $field_value) {
        if ($field_value != "" && in_array($field_name, array_values($expected_fields))) {
            if (is_array($field_value)) {
                $new_data[$field_name] = "NA";
            } else {
                if (!is_null(ord($field_value))) {
                    $new_data[$field_name] = $field_value;
                } else {
                    $new_data[$field_name] = "NA";
                }
            }

        }

    }

    return $new_data;

}
?>
