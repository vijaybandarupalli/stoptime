<?php
// Routes
//
//$app->get('/[{name}]', function ($request, $response, $args) {
//    // Sample log message
//    $this->logger->info("Slim-Skeleton '/' route");
//
//    // Render index view
//    return $this->renderer->render($response, 'index.phtml', $args);
//});


$app->group('/chapterStopApi/v1', function () use ($app) {
 
    // get all Chapter Stops
    $app->get('/cstops', function ($request, $response, $args) {
        $sth = $this->db->prepare("SELECT * FROM chaptersstop_metadata ORDER BY id");
        $sth->execute();
        $todos = $sth->fetchAll();
        return $this->response->withJson($todos);
    });
 
    // Retrieve Chapter Stops with id 
    $app->get('/cstops/[{id}]', function ($request, $response, $args) {
        $sth = $this->db->prepare("SELECT * FROM chaptersstop_metadata WHERE id=:id");
        $sth->bindParam("id", $args['id']);
        $sth->execute();
        $todos = $sth->fetchObject();
        return $this->response->withJson($todos);
    });
 
 
    // Search for Chapter Stops with given search term in their name
    $app->get('/cstops/search/[{query}]', function ($request, $response, $args) {
        $sth = $this->db->prepare("SELECT * FROM chaptersstop_metadata WHERE UPPER(chapter_name) LIKE :query ORDER BY id");
        $query = "%".$args['query']."%";
        $sth->bindParam("query", $query);
        $sth->execute();
        $todos = $sth->fetchAll();
        return $this->response->withJson($todos);
    });
 
    // Add a new Chapter Stop
    $app->post('/todo', function ($request, $response) {
        $input = $request->getParsedBody();
        $sql = "INSERT INTO chaptersstop_metadata (chapter_stoptime) VALUES (:time)";
         $sth = $this->db->prepare($sql);
        $sth->bindParam("time", $input['time']);
        $sth->execute();
        $input['id'] = $this->db->lastInsertId();
        return $this->response->withJson($input);
    });
        
 
    // DELETE a Chapter Stop for a given Title Version
    $app->delete('/todo/[{id}]', function ($request, $response, $args) {
        $sth = $this->db->prepare("DELETE FROM chaptersstop_metadata WHERE id=:id");
        $sth->bindParam("id", $args['id']);
        $sth->execute();
        $todos = $sth->fetchAll();
        return $this->response->withJson($todos);
    });
 
    // Update Chapter Stop for a given Title Version
    $app->put('/cstops/[{id}]', function ($request, $response, $args) {
        $input = $request->getParsedBody();
        $sql = "UPDATE chaptersstop_metadata SET chapter_stoptime=:time WHERE id=:id";
        $sth = $this->db->prepare($sql);
        $sth->bindParam("id", $args['id']);
        $sth->bindParam("time", $input['time']);
        $sth->execute();
        $input['id'] = $args['id'];
        return $this->response->withJson($input);
    });
 
});