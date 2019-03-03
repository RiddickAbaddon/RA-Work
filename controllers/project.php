<?php
session_start();

$db = new Database();

$project_data = $db->get_project($_GET["id"], $_SESSION["user_id"]);
$priorities = $db->get_priorities();
if($project_data["search"]) {
    $this->project_error = false;
    $this->project_data = $project_data["data"];
    $this->project_attachments = $project_data["attachments"];
    $this->project_data["description"] = isset($project_data["data"]["description"]) ? $project_data["data"]["description"] : "Brak opisu";
    $this->set_title($this->project_data["name"]);

    $this->project_stats = array();
    if($this->project_data["type"] != NULL) array_push($this->project_stats, ["key" => "Typ projektu", "value" => $this->project_data["type"]]);
    if($this->project_data["client"] != NULL) array_push($this->project_stats, ["key" => "Klient", "value" => $this->project_data["client"]]);
    if($this->project_data["start_date"] != NULL) array_push($this->project_stats, ["key" => "Data rozpoczęcia prac", "value" => $this->project_data["start_date"]]);
    if($this->project_data["end_date"] != NULL) array_push($this->project_stats, ["key" => "Data zakończenia prac", "value" => $this->project_data["end_date"]]);
    array_push($this->project_stats, ["key" => "Rozliczony", "value" => (int)$this->project_data["settled"] == 1 ? "Tak" : "Nie"]);
    array_push($this->project_stats, ["key" => "Priorytet", "value" => (int)$this->project_data["priority"] == 0 ? "Zwykły" : $priorities[$this->project_data["priority"]]["name"]]);
    $this->priority_color = (int)$this->project_data["priority"] == 0 ? "" : 'style="color:' . $priorities[$this->project_data["priority"]]["color"] . ';"';
    
    if((int)$this->project_data["only_group"] != 0) {
        array_push($this->project_stats, ["key" => "Tylko dla grupy", "value" => $db->get_group_name((int)$this->project_data["only_group"])]);
    }
    
    $this->project_members = $db->get_project_members($_GET["id"]);
} else {
    $this->project_error = true;
}