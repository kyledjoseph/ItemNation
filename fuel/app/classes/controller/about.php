<?php
class Controller_About extends Controller_App
{

    public function get_index()
    {
        $this->template->body = \View::forge('about/index');
    }

    public function action_team()
    {
        $this->template->body = \View::forge('about/team');
    }

    public function action_howitworks()
    {
        $this->template->body = \View::forge('about/howitworks');
    }
}
