-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: tripify_db
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `chatbot_nodes`
--

DROP TABLE IF EXISTS `chatbot_nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatbot_nodes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL,
  `node_type` enum('question','answer') NOT NULL DEFAULT 'question',
  `question_text` varchar(255) DEFAULT NULL,
  `answer_text` text,
  `option_text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chatbot_nodes`
--

LOCK TABLES `chatbot_nodes` WRITE;
/*!40000 ALTER TABLE `chatbot_nodes` DISABLE KEYS */;
INSERT INTO `chatbot_nodes` VALUES (1,NULL,'question','Hi! What would you like help with?',NULL,NULL),(2,1,'question','Choose your transport hub:',NULL,'Transport Info'),(3,1,'question','How much did you spend?',NULL,'Budget Split'),(4,1,'question','What places do you prefer?',NULL,'To-Do Suggestions'),(5,1,'answer',NULL,NULL,'Trip Summary'),(6,2,'answer',NULL,'The airport is 10km away.','Airport'),(7,2,'answer',NULL,'The railway station is 5km away.','Railway Station'),(8,2,'answer',NULL,'The bus terminal is 2km away.','Bus Terminal'),(9,9,'question','Select a city:',NULL,'Mumbai'),(10,9,'question','Select a city:',NULL,'Delhi'),(11,9,'question','Select a city:',NULL,'Chandigarh'),(12,12,'answer',NULL,'The airport in Mumbai is 20km from the city center.','Mumbai Airport'),(13,13,'answer',NULL,'The airport in Delhi is 18km from Connaught Place.','Delhi Airport'),(14,14,'answer',NULL,'The airport in Chandigarh is 12km from Sector 17.','Chandigarh Airport'),(15,2,'question','Which city do you want info for?',NULL,'Airport'),(16,2,'question','Which city do you want info for?',NULL,'Railway Station'),(17,2,'question','Which city do you want info for?',NULL,'Bus Terminal'),(18,15,'question','Select the payer:',NULL,'Alice'),(19,15,'question','Select the payer:',NULL,'Bob'),(20,15,'question','Select the payer:',NULL,'Charlie'),(21,18,'answer',NULL,'Expense recorded and split equally among group members.','Confirm'),(22,3,'question','Who paid for this expense?',NULL,'1000'),(23,3,'question','Who paid for this expense?',NULL,'2000'),(24,3,'question','Who paid for this expense?',NULL,'3000'),(25,21,'answer',NULL,'Top historical spots: Gateway of India, Red Fort, Rock Garden.','Show Me'),(26,22,'answer',NULL,'Top scenic spots: Marine Drive, India Gate, Sukhna Lake.','Show Me'),(27,23,'answer',NULL,'Top food places: Mohammed Ali Road, Chandni Chowk, Sector 17 Plaza.','Show Me'),(28,4,'question','Which type of spot?',NULL,'Historical'),(29,4,'question','Which type of spot?',NULL,'Scenic'),(30,4,'question','Which type of spot?',NULL,'Food');
/*!40000 ALTER TABLE `chatbot_nodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chatbot_questions`
--

DROP TABLE IF EXISTS `chatbot_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatbot_questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL,
  `question_text` varchar(255) DEFAULT NULL,
  `option1` varchar(255) DEFAULT NULL,
  `option2` varchar(255) DEFAULT NULL,
  `option3` varchar(255) DEFAULT NULL,
  `option4` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chatbot_questions`
--

LOCK TABLES `chatbot_questions` WRITE;
/*!40000 ALTER TABLE `chatbot_questions` DISABLE KEYS */;
INSERT INTO `chatbot_questions` VALUES (1,NULL,'Hi! What would you like help with?','Transport Info','Budget Split','To-Do Suggestions','Trip Summary'),(2,1,'Choose your transport hub:','Airport','Railway Station','Bus Terminal',NULL),(3,2,'How much did you spend?','1000','2000','3000',NULL),(4,3,'What places do you prefer?','Historical','Scenic','Food',NULL),(5,4,'Your trip will be saved to summary!',NULL,NULL,NULL,NULL),(6,NULL,'Which city do you want to explore?','Mumbai','Delhi','Chandigarh',NULL),(7,6,'What’s the best time to visit Delhi?','October to March','Summer (if you can handle heat)','Avoid Monsoon',NULL),(8,2,'What are some must-see places in Delhi?','Red Fort','Qutub Minar','Lotus Temple','India Gate'),(9,3,'Looking for budget hotels or luxury?','Budget','Luxury',NULL,NULL),(10,4,'You can check Booking.com for the best Delhi hotels!',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `chatbot_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distances`
--

DROP TABLE IF EXISTS `distances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `from_id` int NOT NULL,
  `to_id` int NOT NULL,
  `from_name` varchar(100) NOT NULL,
  `to_name` varchar(100) NOT NULL,
  `distance_km` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `from_id` (`from_id`),
  KEY `to_id` (`to_id`),
  CONSTRAINT `distances_ibfk_1` FOREIGN KEY (`from_id`) REFERENCES `locations` (`id`),
  CONSTRAINT `distances_ibfk_2` FOREIGN KEY (`to_id`) REFERENCES `locations` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distances`
--

LOCK TABLES `distances` WRITE;
/*!40000 ALTER TABLE `distances` DISABLE KEYS */;
INSERT INTO `distances` VALUES (1,1,4,'New Delhi Railway Station','Red Fort',4.20),(2,1,5,'New Delhi Railway Station','India Gate',7.10),(3,1,6,'New Delhi Railway Station','Qutub Minar',15.30),(4,2,4,'Indira Gandhi Airport (DEL)','Red Fort',18.50),(5,2,5,'Indira Gandhi Airport (DEL)','India Gate',14.20),(6,3,7,'Kashmere Gate ISBT','Lotus Temple',5.80),(7,9,17,'CSMT','Gateway of India',3.00),(8,9,18,'CSMT','Marine Drive',1.50),(9,10,21,'Mumbai Central','Siddhivinayak Temple',4.10),(10,11,22,'Dadar','Juhu Beach',10.50),(11,13,19,'Mumbai Airport','Elephanta Caves',23.20),(12,12,20,'Bandra Terminus','Haji Ali Dargah',9.70);
/*!40000 ALTER TABLE `distances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (8,2,5000.00,'room rent','2025-07-08 12:53:09');
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_expenses`
--

DROP TABLE IF EXISTS `group_expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `group_expenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `payer_name` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `group_expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_expenses`
--

LOCK TABLES `group_expenses` WRITE;
/*!40000 ALTER TABLE `group_expenses` DISABLE KEYS */;
INSERT INTO `group_expenses` VALUES (4,2,'default','Person 3',5000.00,'room rent','2025-07-08 17:52:06');
/*!40000 ALTER TABLE `group_expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` enum('transport','tourist') NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'New Delhi Railway Station','transport',28.64480000,77.21672100,NULL),(2,'Indira Gandhi Airport (DEL)','transport',28.55616000,77.10028100,NULL),(3,'Kashmere Gate ISBT','transport',28.66915600,77.23045300,NULL),(4,'Red Fort','tourist',28.65615900,77.24102000,NULL),(5,'India Gate','tourist',28.61291200,77.22951000,NULL),(6,'Qutub Minar','tourist',28.52447500,77.18552100,NULL),(7,'Lotus Temple','tourist',28.55349200,77.25882600,NULL),(8,'Chandni Chowk','tourist',28.65615900,77.23045300,NULL),(9,'Chhatrapati Shivaji Maharaj Terminus (CSMT)','transport',18.94017000,72.83593000,NULL),(10,'Mumbai Central Railway Station','transport',18.96957000,72.81951900,NULL),(11,'Dadar Railway Station','transport',19.01838300,72.84465800,NULL),(12,'Bandra Terminus','transport',19.05443000,72.84029000,NULL),(13,'Chhatrapati Shivaji Maharaj International Airport (BOM)','transport',19.08956000,72.86561400,NULL),(14,'Mumbai Central Bus Depot','transport',18.97568300,72.82034800,NULL),(15,'Kurla Bus Depot','transport',19.07211100,72.88436500,NULL),(16,'Gateway of India','tourist',18.92198400,72.83465400,NULL),(17,'Marine Drive','tourist',18.94321200,72.82338400,NULL),(18,'Elephanta Caves','tourist',18.96334600,72.93117300,NULL),(19,'Haji Ali Dargah','tourist',18.98277900,72.80803300,NULL),(20,'Juhu Beach','tourist',19.09880200,72.82676600,NULL),(21,'Siddhivinayak Temple','tourist',19.01685000,72.83057900,NULL),(22,'Chhatrapati Shivaji Maharaj Vastu Sangrahalaya','tourist',18.92687300,72.83261300,NULL);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `places`
--

DROP TABLE IF EXISTS `places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `places` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `places`
--

LOCK TABLES `places` WRITE;
/*!40000 ALTER TABLE `places` DISABLE KEYS */;
INSERT INTO `places` VALUES (1,'Gateway of India','Mumbai',18.921984,72.834654),(2,'Marine Drive','Mumbai',18.9435,72.8235),(3,'Elephanta Caves','Mumbai',18.9633,72.9314),(4,'Red Fort','Delhi',28.6562,77.241),(5,'India Gate','Delhi',28.6129,77.2295),(6,'Qutub Minar','Delhi',28.5244,77.1855),(7,'Rock Garden','Chandigarh',30.7525,76.81),(8,'Sukhna Lake','Chandigarh',30.7428,76.8104),(9,'Rose Garden','Chandigarh',30.7445,76.7821),(10,'Elante Mall','Chandigarh',30.7046,76.802),(11,'Sector 17 Plaza','Chandigarh',30.7412,76.783),(12,'Government Museum and Art Gallery','Chandigarh',30.7482,76.7831),(13,'Capitol Complex','Chandigarh',30.7599,76.8035);
/*!40000 ALTER TABLE `places` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `task` varchar(255) NOT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (9,2,'Go to beach','pending','2025-07-09','2025-07-08 13:01:58'),(10,2,'Head back','completed','2025-07-09','2025-07-08 13:02:17');
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transport_distances`
--

DROP TABLE IF EXISTS `transport_distances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transport_distances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `city` varchar(50) NOT NULL,
  `station_name` varchar(100) NOT NULL,
  `distance_km` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transport_distances`
--

LOCK TABLES `transport_distances` WRITE;
/*!40000 ALTER TABLE `transport_distances` DISABLE KEYS */;
INSERT INTO `transport_distances` VALUES (1,'Mumbai','Chhatrapati Shivaji Maharaj Terminus',0),(2,'Mumbai','Mumbai Central',5.5),(3,'Mumbai','Bandra Terminus',15),(4,'Delhi','New Delhi Railway Station',0),(5,'Delhi','Old Delhi Railway Station',3.5),(6,'Delhi','Hazrat Nizamuddin',10),(7,'Chandigarh','Chandigarh Railway Station',0),(8,'Chandigarh','Industrial Area',7),(9,'Chandigarh','Sector 17',3);
/*!40000 ALTER TABLE `transport_distances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `travel_shorts`
--

DROP TABLE IF EXISTS `travel_shorts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `travel_shorts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `video_path` varchar(255) NOT NULL,
  `caption` text,
  `city` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `travel_shorts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `travel_shorts`
--

LOCK TABLES `travel_shorts` WRITE;
/*!40000 ALTER TABLE `travel_shorts` DISABLE KEYS */;
INSERT INTO `travel_shorts` VALUES (2,2,'uploads/686a9ed617881.mp4','New','Chandigarh','2025-07-06 21:35:42'),(3,2,'uploads/686ab8c07cdf0.mp4','Having Fun just chilling','Mumbai','2025-07-06 23:26:16'),(4,2,'uploads/686d1643db07d.mp4','tsch','Mumbai','2025-07-08 18:29:47'),(5,2,'uploads/686d170899cc6.mp4','Funnnn','Chandigarh','2025-07-08 18:33:04'),(6,2,'uploads/686d172bd876d.mp4','Yahhhhh.... Tooo much funnnnnnnnnnnnnnnnnnnnn','Chandigarh','2025-07-08 18:33:39');
/*!40000 ALTER TABLE `travel_shorts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','rahulsaxena23012004@gmail.com','$2y$10$1gulFuk/MYNcxWGYBEkVcOrJSd81tHeIC4SMT7vMgPQdZ387x00PK','2025-06-30 15:17:42'),(2,'Rahul','24MCI10204@cuchd.in','$2y$10$4z3b12IkWESUJT2O9S1RAexjuZk3v5JERp78aw2s5rzX453rTPvTO','2025-07-02 17:03:05');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-08 23:23:44
