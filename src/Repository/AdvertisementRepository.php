<?php

namespace src\Repository;

class AdvertisementRepository
{

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Insert new advertisement
     *
     * @return array|string
     */
    public function insert()
    {
        $query = "
            INSERT INTO advertisement 
                (text, price, limit_of_impressions, banner)
            VALUES
                (:text, :price, :limit_of_impressions, :banner);
        ";

        try {
            $query = $this->db->prepare($query);
            $query->execute(array(
                'text' => $_POST['text'],
                'price' => $_POST['price'],
                'limit_of_impressions' => $_POST['limit'],
                'banner' => $_POST['banner']
            ));

            return [
                'id' => $this->db->lastInsertId(),
                'text' => $_POST['text'],
                'banner' => $_POST['banner']
            ];
        } catch (\PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get relevant
     *
     * @return mixed
     */
    public function getRelevant()
    {
        $query = "
            SELECT 
                id, text, banner
            FROM
                advertisement
            WHERE price=(SELECT MAX(price) FROM advertisement WHERE number_of_impressions <= limit_of_impressions) AND number_of_impressions <= limit_of_impressions;
        ";

        try {
            $query = $this->db->prepare($query);
            $query->execute();
            return $query->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * Adding a impressions for displaying ads
     *
     * @param $id
     * @return mixed
     */
    public function addImpressions($id)
    {
        $advertisement = $this->find($id);
        $numberOfImpressions = $advertisement['number_of_impressions'];
        $numberOfImpressions++;

        $query = "
            UPDATE advertisement
            SET 
                number_of_impressions = :number_of_impressions
            WHERE id = :id;
        ";

        try {
            $query = $this->db->prepare($query);
            $query->execute(array(
                'id' => (int)$id,
                'number_of_impressions' => $numberOfImpressions
            ));
            return $query->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * Find advertisement by id
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $query = "
            SELECT 
                id, text, price, limit_of_impressions, banner, number_of_impressions
            FROM
                advertisement
            WHERE id = ?;
        ";

        try {
            $query = $this->db->prepare($query);
            $query->execute(array($id));
            return $query->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * Update advertisement
     *
     * @param $id
     * @return array
     */
    public function update($id)
    {
        $query = "
            UPDATE advertisement
            SET 
                text = :text,
                price = :price,
                limit_of_impressions = :limit_of_impressions,
                banner = :banner
            WHERE id = :id;
        ";

        try {
            $query = $this->db->prepare($query);
            $query->execute(array(
                'id' => (int)$id,
                'text' => $_POST['text'],
                'price' => $_POST['price'],
                'limit_of_impressions' => $_POST['limit'],
                'banner' => $_POST['banner']
            ));
            return [
                'id' => $id,
                'text' => $_POST['text'],
                'banner' => $_POST['banner']
            ];
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}