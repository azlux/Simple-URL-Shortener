package config

import (
	"database/sql"
	"log"

	_ "github.com/go-sql-driver/mysql" // MySQL
	_ "github.com/lib/pq"              // PostgreSQL
	_ "modernc.org/sqlite"
)

func ConnectDatabase(driver, dsn string) *sql.DB {
	// dns for Database Source Name
	// SQLite: "urls.db"
	// PostgreSQL: "postgres://user:pass@localhost/dbname?sslmode=disable"
	// MySQL: "user:pass@tcp(localhost:3306)/dbname"
	db, err := sql.Open(driver, dsn)
	if err != nil {
		log.Fatalf("Cannot open the \"%s\" Database config/file \"%s\" , Error %s", driver, dsn, err)
	}
	if db.Ping() == nil {
		log.Fatal("Cannot connect/ping the Database")
	}
	return db
}

func EnsureDataBaseTablesExist(driver string, db *sql.DB) error {
	var query string
	switch driver {
	case "sqlite":
		query = `
			CREATE TABLE IF NOT EXISTS urls (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				short TEXT UNIQUE,
				original TEXT
			);`
	default:
		query = `
			CREATE TABLE IF NOT EXISTS urls (
				id SERIAL PRIMARY KEY,
				short VARCHAR(255) UNIQUE NOT NULL,
				original TEXT NOT NULL
			);`
	}
	_, err := db.Exec(query)
	return err
}
