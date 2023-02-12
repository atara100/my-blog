<?php

class Post
{
    protected
        $id, $title, $last_update, $author, $user_id;

    function __construct($id, $title, $last_update, $author, $user_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->last_update = $last_update;
        $this->author = $author;
        $this->user_id = $user_id;
    }

    public function set($property, $value)
    {
        $this->$property = $value;
    }

    public function get($property)
    {
        return $this->$property;
    }

    public function formatDate()
    {
        $time = strtotime($this->last_update);
        $date = date('Y-m-d H:i:s', $time);
        return $date;
    }
}

class TextPost extends Post
{
    protected $post_body;

    function __construct($id, $title, $last_update, $author, $user_id, $post_body)
    {
        parent::__construct($id, $title, $last_update, $author, $user_id);
        $this->post_body = $post_body;
    }
}

class ImagePost extends Post
{
    protected $image_url, $image_alt;

    function __construct($id, $title, $last_update, $author, $user_id, $image_url, $image_alt)
    {
        parent::__construct($id, $title, $last_update, $author, $user_id);
        $this->image_url = $image_url;
        $this->image_alt = $image_alt;
    }
}
