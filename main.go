package main

import (
	"log"
	"os"

	"git.azlux.fr/Azlux/simple-url-shortener/config"
	"git.azlux.fr/Azlux/simple-url-shortener/controllers"

	_ "git.azlux.fr/Azlux/simple-url-shortener/docs"
	"github.com/gin-gonic/gin"
	swaggerFiles "github.com/swaggo/files"
	ginSwagger "github.com/swaggo/gin-swagger"

	"github.com/joho/godotenv"
)

func getEnvDefault(key, fallback string) string {
	value, exists := os.LookupEnv(key)
	if !exists {
		value = fallback
	}
	return value
}

func main() {

	// Charge le fichier .env
	err := godotenv.Load()
	if err != nil {
		log.Println("No .env file found, using system environment variables")
	}

	driver := getEnvDefault("DB_DRIVER", "sqlite")
	dsn := getEnvDefault("DB_DSN", "file://test.db")

	r := gin.Default()
	db := config.ConnectDatabase(driver, dsn)
	defer db.Close()

	if err := config.EnsureDataBaseTablesExist(driver, db); err != nil {
		log.Fatalf("Table creation failed: %v", err)
	}

	// Add the open database in Gin context
	r.Use(func(c *gin.Context) {
		c.Set("db", db)
		c.Next()
	})

	// Swagger route for docs
	r.GET("/swagger/*any", ginSwagger.WrapHandler(swaggerFiles.Handler))

	r.POST("/shorten", controllers.CreateShortURL)
	r.GET("/:code", controllers.GetOriginalURL)

	r.Run(":8080")
}
