const msg = "SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'gabikochem55@gmail.com' for key 'owners_email_unique' (Connection: mariadb, SQL: insert into `owners` (`owner_code`, `name`, `email`, `phone`, `password`, `updated_at`, `created_at`) values (2, asdasasd, gabikochem55@gmail.com, ?, $2y$12$L5c1/2pZ7kKbRzdK3cq1getp74PtmGbTFb/BV3XdAf52uw1j1wAVe"

const objective = "SQLSTATE[23000]";

console.log(msg.trim().includes(objective));