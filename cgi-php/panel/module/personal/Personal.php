<?php

namespace Run\panel\module\personal;

use Run\data\db\Mysqli;

class Personal extends \Run\panel\core\main\Main {

    private $mail, $user, $created, $time, $date,
            $query = "
SELECT
	mail,
        date_created
FROM
	panel_user
WHERE
	panel_user.user = '[U]'";

    public function __construct($param)
    {
        parent::__construct($param);
        parent::view([
            'content' => $this->_content()
        ]);
    }

    private function _content()
    {
        $this->_created($this->_mysql());
        $view = $this->_view();
        return str_replace(
                array_keys($view),
                array_values($view),
                file_get_contents(__DIR__ . '/personal.tpl')
        );
    }

    private function _mysql()
    {
        $this->user = filter_input(2, 'panel:user');
        $query = str_replace('[U]', $this->user, $this->query);
        $row = Mysqli::fetch_assoc($query);
        $this->mail = $row['mail'];
        $this->created = $row['date_created'];
    }

    private function _created()
    {
        parent::setTimestamp($this->created);
        $d = explode(' ', parent::format('Y d m H i'));
        $this->time = $d[3] . ':' . $d[4];
        $this->date = $d[1] . '.' . $d[2] . '.' . $d[0];
    }

    private function _view()
    {
        return[
            '{ LE:MAIL }' => $this->le['mail'],
            '{ MAIL }' => $this->mail,
            '{ LE:USER }' => $this->le['user'],
            '{ USER }' => $this->user,
            '{ LE:EDIT }' => $this->le['edit'],
            '{ LE:CREATED }' => $this->le['created'],
            '{ TIME }' => $this->time,
            '{ DATE }' => $this->date,
            '{ LE:PASSWORD }' => $this->le['password'],
            '{ LE:CHANGE_PASSWORD }' => $this->le['change_password'],
        ];
    }

}
