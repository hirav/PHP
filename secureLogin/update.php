<?php include_once("header.php"); include_once("user.php"); 

if(!empty($_POST))
{

    foreach($_POST as $field_name => $val)
    {
        //clean post values
        $field_id = strip_tags(trim($field_name));

        //from the fieldname:user_id we need to get user_id
        $split_data = explode(':', $field_id);
        $admin_id = $split_data[1];
        $field_name = $split_data[0];
        if(!empty($admin_id) && !empty($field_name) && !empty($val))
        {
            if(strpos($field_name, 'admin_') !== false)
            {
                $user = new User();
                $result = $user->editUser($admin_id, $field_name, $val);
            }
            if(strpos($field_name, 'widget_') !== false)
            {
                $widget = new Widget();
                $result = $widget->editWidget($admin_id, $field_name, $val);
            }
            if($result){
                echo $result." value updated";
            }    
        }
        else 
        {
            echo "Invalid Requests";
        }
    }
} 
else {
    echo "Invalid Requests";
}
?>