// query to create todo table


CREATE TABLE TODO (
    id SERIAL PRIMARY KEY,
    task VARCHAR(100), 
    is_done SMALLINT DEFAULT 0,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);