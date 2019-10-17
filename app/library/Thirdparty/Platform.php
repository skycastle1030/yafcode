<?php

//禁止修改
class Thirdparty_Platform
{
    const USER_ID_BASIC_LENGTH = 10;
    const USER_BASIC_GROUP_ID_LENGTH = 5;
    const BRANCH_ID_BASIC_LENGTH = 5;

    //第三方平台对应的用户分组ID生成规则
    public static function userGroupIdGenerate($platform_id, $branch_id, $role_id)
    {
        $branch_id = self::branchIdGenerate($platform_id, $branch_id);
        $role_id = self::userBasicGroupIdGenerate($platform_id, $role_id);
        return $branch_id . $role_id;
    }

    //第三方平台对应的门店ID生成规则
    public static function branchIdGenerate($platform_id, $branch_id)
    {
        //new_branch_id 五位数
        $new_branch_id = $branch_id;
        for ($i = 0; $i < BRANCH_ID_BASIC_LENGTH - strlen($branch_id); $i++) {
            $new_branch_id = "0" . $new_branch_id;
        }
        return $platform_id . $new_branch_id;
    }

    //第三方平台对应的用户基础分组ID生成规则
    public static function userBasicGroupIdGenerate($platform_id, $role_id)
    {
        //new_role_id 三位数
        $new_role_id = $role_id;
        for ($i = 0; $i < self::USER_BASIC_GROUP_ID_LENGTH - strlen($role_id); $i++) {
            $new_role_id = "0" . $new_role_id;
        }
        return $platform_id . $new_role_id;
    }

    //第三方平台对应的用户ID生成规则
    public static function userIdGenerate($platform_id, $user_id)
    {
        //new_user_id 五位数
        $new_user_id = $user_id;
        for ($i = 0; $i < self::USER_ID_BASIC_LENGTH - strlen($user_id); $i++) {
            $new_user_id = "0" . $new_user_id;
        }
        return $platform_id . $new_user_id;
    }

    //
    public static function userIdRestore($platform_id, $user_id)
    {
        return intval(substr($user_id, strlen($platform_id), strlen($user_id)));
    }
}
