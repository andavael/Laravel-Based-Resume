-- Drop existing tables safely
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    sr_code VARCHAR(20) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS resume CASCADE;

CREATE TABLE resume (
    id SERIAL PRIMARY KEY,
    full_name TEXT NOT NULL,
    nickname TEXT,
    title TEXT,
    university TEXT,
    description TEXT,
    personal_info JSONB,
    education JSONB,
    leadership JSONB,
    interests JSONB,
    awards JSONB,
    projects JSONB,
    email TEXT,
    phone TEXT,
    address TEXT
);

-- Rename password_hash to password (as before)
ALTER TABLE users RENAME COLUMN password_hash TO password;

DROP TABLE IF EXISTS resume CASCADE;
DROP TABLE IF EXISTS users CASCADE;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    sr_code VARCHAR(20) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE resume (
    id SERIAL PRIMARY KEY,
    full_name TEXT NOT NULL,
    nickname TEXT,
    title TEXT,
    university TEXT,
    description TEXT,
    personal_info JSONB,
    education JSONB,
    leadership JSONB,
    interests JSONB,
    awards JSONB,
    projects JSONB,
    email TEXT,
    phone TEXT,
    address TEXT
);

ALTER TABLE users RENAME COLUMN password_hash TO password;

