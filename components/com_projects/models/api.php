<?php
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

defined('_JEXEC') or die;

class ProjectsModelApi extends BaseDatabaseModel
{
    public function __construct($config = array())
    {
        $this->api_key = JFactory::getApplication()->input->getString('api_key', '');
        if (!$this->checkKey() || $this->api_key == '') exit();
        parent::__construct($config);
    }

    /**
     * Возвращает соль на текущий день
     * @return int
     * @since 1.2.0.0
     */
    public function getSalt(): int
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("cast(json_extract(params,'$.aes_key') as signed)")
            ->from("`#__extensions`")
            ->where("`element` like 'com_projects'");
        return $db->setQuery($query)->loadResult() ?? 0;
    }

    /**
     * Возвращаает список всех экспонентов
     * @return array
     * @since 1.2.0.0
     */
    public function getExhibitors(): array
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("*")
            ->from("`#__prj_exhibitors_all`");
        return $db->setQuery($query)->loadObjectList() ?? array();
    }

    /**
     * Регистрация компании в системе
     * @throws Exception
     * @since 1.2.0.0
     */
    public function registerUser(): int
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $email = JFactory::getApplication()->input->getString('email', '');
        if ($email == '' || $id == 0) return 0;
        $data['username'] = $email;
        $data['name'] = $email;
        $data['email'] = $email;
        $data['password'] = $this->getPasswordFromUrl();
        $data['groups'] = array(2);
        $user = new JUser;
        $user->bind($data);
        $user->save();
        $uid = $user->id;
        $this->updateExhibitorUserId($id, $uid);
        return $uid;
    }

    /**
     * Привязывает ID учётной записи к ID компании
     * @param int $exhibitorID ID компании
     * @param int $userID ID учётной записи юзера
     * @since 1.2.0.0
     */
    private function updateExhibitorUserId(int $exhibitorID = 0, int $userID = 0): void
    {
        if ($exhibitorID == 0 || $userID == 0) return;
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->update("`#__prj_exp`")
            ->set("`user_id` = {$userID}")
            ->where("`id` = {$exhibitorID}");
        $db->setQuery($query, 0, 1)->execute();
    }

    /**
     * Возвращает дешифрованный пароль из адресной строки
     * @return string
     * @throws Exception
     * @since 1.2.0.0
     */
    private function getPasswordFromUrl(): string
    {
        $password = JFactory::getApplication()->input->getString('password', '');
        if ($password == '') return '';
        $db =& JFactory::getDbo();
        $password = $db->q($password);
        $query = $db->getQuery(true);
        $query
            ->select("decode(FROM_BASE64({$password}), cast(json_extract(params,'$.aes_key') as signed))")
            ->from("`#__extensions`")
            ->where("`element` like 'com_projects'");
        return $db->setQuery($query)->loadResult() ?? '';
    }

    /**
     * Проверка ключа доступа к API
     * @return bool
     * @since 1.2.0.0
     */
    private function checkKey(): bool
    {
        $db =& JFactory::getDbo();
        $k = $db->q($this->api_key);
        $query = $db->getQuery(true);
        $query
            ->select("IFNULL(`id`,0)")
            ->from("`#__api_keys`")
            ->where("`api_key` LIKE {$k}");
        $result = (int) $db->setQuery($query)->loadResult() ?? 0;
        return ($result == 0) ? false : true;
    }

    private $api_key;
}
