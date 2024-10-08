<?php

namespace block_mvcuserlist\actions;

use block_mvcuserlist\AbstractAction;

class UserGradeAction extends AbstractAction
{
    public function execute()
    {
        global $DB, $OUTPUT;
        //$action = required_param('action', PARAM_TEXT);
        //var_dump($action);
        if (data_submitted() && confirm_sesskey()/* && $action == 'with_mvc'*/) {
            $action = required_param('action', PARAM_TEXT);
            if ($action == 'with_mvc') {
                $userid = required_param('userid', PARAM_INT);
                $grade = required_param('grade', PARAM_INT);

                $record = new \stdClass();
                $record->userid = $userid;
                $record->grade = $grade;

                if ($existing = $DB->get_record('block_mvcuserlist_grades', ['userid' => $userid])) {
                    $record->id = $existing->id;
                    $DB->update_record('block_mvcuserlist_grades', $record);
                } else {
                    $DB->insert_record('block_mvcuserlist_grades', $record);
                }

                return $OUTPUT->notification('Оценка успешно выставлена!', 'success');
            }
        }

        //return $OUTPUT->notification('Ошибка при выставлении оценки.', 'success');
    }
}
