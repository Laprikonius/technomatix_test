<?php
defined('MOODLE_INTERNAL') || die();

use \block_mvcuserlist\controllers\UserGradeController;

class block_mvcuserlist extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_mvcuserlist');
    }

    public function get_content() {
        global $OUTPUT, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';

        $userCanEdit = true;

        try {
            $action = 'gradeUser';
            $controller = block_mvcuserlist\controllers\UserGradeController::getInstance($action);
            $this->content->text .= $controller->execute();
        } catch (\Exception $e) {
            //$this->content->text .= $OUTPUT->notification($e->getMessage(), 'error');
        }

        $this->content->text .= $this->display_users();

        if ($userCanEdit) {
            $this->content->text .= html_writer::start_tag('form', ['method' => 'post', 'action' => '']);
            $this->content->text .= html_writer::start_tag('input', [
                'type' => 'hidden',
                'name' => 'sesskey',
                'value' => sesskey(),
                'required' => 'required'
            ]);

            $this->content->text .= html_writer::start_tag('input', [
                'type' => 'hidden',
                'name' => 'action',
                'value' => 'with_mvc',
                'required' => 'required'
            ]);
    
            $users = $DB->get_records('user', null, '', 'id, CONCAT(firstname, " ", lastname) AS name');
            $options = array_column($users, 'name', 'id');
            $this->content->text .= html_writer::select($options, 'userid', '', 'Выберите пользователя');
    
            $this->content->text .= html_writer::start_tag('input', [
                'type' => 'text',
                'name' => 'grade',
                'maxlength' => '2',
                'placeholder' => 'Оценка (1-10)',
                'required' => 'required'
            ]);
    
            $this->content->text .= html_writer::start_tag('input', [
                'type' => 'submit',
                'value' => 'Выставить оценку'
            ]);
    
            $this->content->text .= html_writer::end_tag('form');
        }

        return $this->content;
    }

    public function display_users() {
        global $DB;
    
        $users = $DB->get_records('user', null, '', 'id, firstname, lastname, email');
        $grades = $DB->get_records('block_mvcuserlist_grades');
    
        $table = new html_table();
        $table->head = ['ID', 'Имя', 'Фамилия', 'E-mail', 'Оценка'];
    
        foreach ($users as $user) {
            $grade = isset($grades[$user->id]) ? $grades[$user->id]->grade : 'Не оценен';
            $table->data[] = [$user->id, $user->firstname, $user->lastname, $user->email, $grade];
        }
    
        return html_writer::table($table);
    }
}
