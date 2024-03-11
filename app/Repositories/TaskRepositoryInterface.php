<?php
namespace App\Repositories;

interface TaskRepositoryInterface{
    public function addTask($arr);
    public function getDetails($id);
    public function getAllTassk();
    public function updateTask($id,$request);
    public function showByUser($id);
    public function filterAll($request);
    public function fillterWithUser($id,$request);
}