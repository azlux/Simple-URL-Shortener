package routes

import (
	"git.azlux.fr/Azlux/simple-url-shortener/controllers"

	"github.com/gin-gonic/gin"
)

func RegisterRoutes(r *gin.Engine) {
	r.POST("/shorten", controllers.CreateShortURL)
	r.GET("/:code", controllers.GetOriginalURL)
}
