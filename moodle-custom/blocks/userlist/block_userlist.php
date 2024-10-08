<?php
class block_userlist extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_userlist');
    }

    public function get_content() {
        global $OUTPUT, $USER, $DB;
    
        if ($this->content !== null) {
            return $this->content;
        }
    
        $this->content = new stdClass;
        $this->content->text = '';

        $userCanEdit= true;

        $sesskey = optional_param('sesskey', '', PARAM_RAW);
        if (!empty($sesskey) && !confirm_sesskey($sesskey)) {
            print_error('invalidsesskey', 'error');
        } else {
            //echo print_r($sesskey);
        }
        //$action = required_param('action', PARAM_TEXT);
        if (data_submitted() && isset($sesskey)/* && $action == 'not_mvc'*/) {
            //var_dump(required_param('action', PARAM_TEXT));
            $action = required_param('action', PARAM_TEXT);
            if ($action == 'not_mvc') {
                $userid = required_param('userid', PARAM_INT);
                $grade = required_param('grade', PARAM_INT);
        
                $record = new stdClass();
                $record->userid = $userid;
                $record->grade = $grade;
        
                if ($existing = $DB->get_record('block_userlist_grades', ['userid' => $userid])) {
                    $record->id = $existing->id;
                    $DB->update_record('block_userlist_grades', $record);
                } else {
                    $DB->insert_record('block_userlist_grades', $record);
                }
        
                $this->content->text .= $OUTPUT->notification('Оценка успешно выставлена!', 'success');
            }
        }
    
        $this->content->text .= $this->display_users();
    
        if ($userCanEdit) {
            $this->content->text .= html_writer::start_tag('form', ['method' => 'post', 'action' => '']);
    
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
                'type' => 'hidden',
                'name' => 'action',
                'value' => 'not_mvc',
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
        $grades = $DB->get_records('block_userlist_grades');
    
        $table = new html_table();
        $table->head = ['ID', 'Имя', 'Фамилия', 'E-mail', 'Оценка'];
    
        foreach ($users as $user) {
            $grade = isset($grades[$user->id]) ? $grades[$user->id]->grade : 'Не оценен';
            $table->data[] = [$user->id, $user->firstname, $user->lastname, $user->email, $grade];
        }
    
        return html_writer::table($table);
    }
}
