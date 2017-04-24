<?php
namespace Star\Excel;
use \Maatwebsite\Excel\Files\ExportHandler;

class UserListExport implements ExportHandler
{
    public function getFileName()
    {
        return '内部管理人员表';
    }
    
}