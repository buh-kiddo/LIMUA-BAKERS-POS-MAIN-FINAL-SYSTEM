<?php

trait Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        
        if(file_exists("../app/views/" . $view . ".view.php"))
        {
            require "../app/views/" . $view . ".view.php";
        }else{
            require "../app/views/404.view.php";
        }
    }

    public function load_model($model)
    {
        if(file_exists("../app/models/" . ucfirst($model) . ".php"))
        {
            require "../app/models/" . ucfirst($model) . ".php";
            return new $model();
        }
        
        return false;
    }

    public function redirect($link)
    {
        header("Location: index.php?pg=" . $link);
        die;
    }

    protected function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    protected function handleImageUpload($field, $folder)
    {
        if(!empty($_FILES[$field]['name']))
        {
            $allowed[] = "image/jpeg";
            $allowed[] = "image/png";
            $allowed[] = "image/gif";
            
            if(in_array($_FILES[$field]['type'], $allowed))
            {
                $destination = $folder . time() . "_" . $_FILES[$field]['name'];
                move_uploaded_file($_FILES[$field]['tmp_name'], $destination);
                return $destination;
            }
        }
        
        return '';
    }
}
