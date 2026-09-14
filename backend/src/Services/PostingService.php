<?php 
namespace Konektem\Services;
use Konektem\Utils\Log;

Log::init();
class PostingService{
    private $model;
    private $itemName;
    private $itemId;
    private $userPayload;
    private $cb;
    

    public function __construct(){
        $this->model = null;
        $this->itemName = null;
        $this->itemId = null;
        $this->userPayload = [];
        $this->cb = null;
    }

    public function publish($req, $res){
        $this->model = new \Konektem\Models\Database();
        $pdo = $this->model->getPdo();
        try {
            $this->userPayload = (new \Konektem\Auth\Auth())->getAuthTokenPayload();
            if(empty($this->userPayload)){
                return $res->status(401)->json([
                    'success' => false,
                    'message' => 'Login required'
                ]);
            }
            
            $data = (array)$req->body ?: json_decode(file_get_contents('php://input'), true);
            if(empty($data)){
                return $res->status(400)->json([
                    'success' => false,
                    'message' => 'Empty content array'
                ]);
            }
                        
            $sections = array_reduce($data, function($group, $item){
                $item = (array)$item;
                if(!array_key_exists($item['section'], $group)){
                    $group[$item['section']] = [];
                }
                $group[$item['section']][] = $item['id'];
                return $group;
            }, []);

            if(empty($sections)){
                return $res->status(400)->json([
                    'success' => false,
                    'message' => 'Empty sections group'
                ]);
            }
            
            $pdo->beginTransaction();
            $publishCount = 0;
            $updatedItems = [];
            $hasPremiumSubscription = isset($this->userPayload['subscription']) && !empty($this->userPayload['subscription']);
            $mainPageAddCount = 0;

            // Map section to table name
            $tableMap = [
                'music' => 'music',
                'events' => 'events',
                'books' => 'books',
                // Add more mappings as needed
            ];
            foreach($sections as $section => $itemIds){
                if(empty($itemIds)) {
                    continue;
                }
                
                if(!isset($tableMap[$section])){
                    Log::warning("Unknown section: {$section}");
                    continue;
                }
                
                $table = $tableMap[$section];
                
                // Method 1: Using placeholders with implode (Recommended)
                $placeholders = implode(',', array_fill(0, count($itemIds), '?'));
                $sql = "UPDATE {$table} SET public = 1 WHERE id IN ({$placeholders})";
                
                $stmt = $pdo->prepare($sql);
                
                // Bind each value
                foreach($itemIds as $index => $id) {
                    $stmt->bindValue($index + 1, $id, \PDO::PARAM_INT);
                }
                
                $stmt->execute();
                $publishCount += $stmt->rowCount();
                
                
                // Store updated item IDs for logging
                $updatedItems[$section] = $itemIds;

            }

            if($publishCount === 0){
                return $res->json([
                    'success' => false,
                    'message' => 'Failed to publish content. No items were updated.'
                ]);
            }

            if($hasPremiumSubscription){
                $mpModel = new \Konektem\Models\MainPageContentModel();
                foreach($updatedItems as $section => $ids){
                    $position = $tableMap[$section];
                    foreach($ids as $id){
                        $mainPageAddCount += $mpModel->addItem($position, $id) ? 1 : 0;
                    }
                }
                
            }
            $pdo->commit();

            // Send email notification
            $email = new \Konektem\Models\EmailModel();
            $subject = 'Content Publishing Confirmation';
            $toEmail = $this->userPayload['email'];
            $toName = $this->userPayload['name'];
            
            // Build a better email body with published items
            $bodyHtml = $this->buildPublishEmailBody($updatedItems, $publishCount);
            
            $result = $email->prepare($subject, $toEmail, $toName, $bodyHtml)->send();
            $response = [
                'success' => true,
                'message' => "Successfully published {$publishCount} items",
                'data' => [
                    'published_count' => $publishCount,
                    'sections' => $updatedItems
                ]
            ];
            if($hasPremiumSubscription){
                $response['data']['mainpage_add_count'] = $mainPageAddCount;
            }
            return $res->json($response);
            
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            $pdo->rollBack();
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        } catch(\Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            $pdo->rollBack();
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    /**
     * Build email body HTML for publishing confirmation
     */
    private function buildPublishEmailBody($updatedItems, $totalCount) {
        $html = '<h2>Content Published Successfully</h2>';
        $html .= "<p>You have successfully published {$totalCount} items.</p>";
        $html .= '<h3>Published Items:</h3><ul>';
        
        foreach($updatedItems as $section => $itemIds) {
            $html .= "<li><strong>" . ucfirst($section) . ":</strong> " . count($itemIds) . " items (IDs: " . implode(', ', $itemIds) . ")</li>";
        }
        
        $html .= '</ul>';
        $html .= '<p>Thank you for using Konektem!</p>';
        
        return $html;
    }
}

?>