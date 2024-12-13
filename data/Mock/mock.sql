INSERT INTO `role` VALUES (1,'Utilisateur','role.user'),(2,'Administrateur','role.admin'),(3,'fondateur','role.owner'),(4,'moderateur','role.modo');
INSERT INTO `role_hierarchy` VALUES (1,3,2),(2,2,4),(3,4,1);
-- create user with credentials phoenix/testpass
INSERT INTO `user` VALUES (1,'Phoenix','$2y$10$yj4dBIiXKUG9j9Ii4Hl8qOUfc6qGPYbs2oYQ5Eg98N/OF9BrhLUC2','phoenix@yopmail.com');
INSERT INTO `user_role` VALUES (2,1,4),(1,2,2);