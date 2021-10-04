<?php

    namespace Exams;

    class ExamsModel
    {
        public $id;
        public $creator_id;
        public $class_id;
        public $subject_id;
        public $subject;
        public $class;
        public $date;
        public $room;
        public $topic;
        public $other;
        public $created_at;
        public $changed_at;
        public $lessonFrom;
        public $lessonTo;

        public function listExams() {
            $rtn = new \stdClass;
            $rtn->subject = $this->subject;
            $rtn->class = $this->class;
            $rtn->room = $this->room;
            $rtn->lessonFrom = $this->lessonFrom;
            $rtn->lessonTo = $this->lessonTo;

            return $rtn;
        }
    }
