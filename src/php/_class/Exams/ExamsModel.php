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
        public $timeFrom;
        public $timeTo;
    }
