CREATE TABLE companies (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    website VARCHAR(255),
    address TEXT,
    source VARCHAR(50), -- Es: 'API_1', 'SCRAPER_2', 'MANUAL'
    inserted_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE normalized_companies (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    canonical_website VARCHAR(255),
    address TEXT
);

INSERT INTO companies (name, website, address, source) VALUES
('OpenAI', 'https://openai.com', 'San Francisco', 'MANUAL'),
('Innovatiespotter', 'https://innovatiespotter.com', 'Groningen', 'API'),
('Apple', 'https://apple.com', 'Cupertino', 'SCRAPER');
