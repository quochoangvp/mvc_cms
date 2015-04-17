<?php
header('Content-Type: application/json');

$data = array();

if (isset($status)) $data['status'] = $status;

if (isset($msg)) $data['msg'] = $msg;

if (isset($url)) $data['url'] = $url;

if (isset($id)) $data['id'] = $id;

if (isset($eid)) $data['eid'] = $eid;

echo json_encode($data);