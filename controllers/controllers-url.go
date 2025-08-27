package controllers

import (
	"database/sql"
	"net/http"

	"git.azlux.fr/Azlux/simple-url-shortener/models"

	"github.com/gin-gonic/gin"
	"github.com/google/uuid"
)

// CreateShortURL godoc
// @Summary Crée une URL raccourcie
// @Description Génère un code court pour une URL longue
// @Accept json
// @Produce json
// @Param url body models.URL true "URL à raccourcir"
// @Success 200 {object} models.URL
// @Failure 400 {object} map[string]string
// @Router /shorten [post]
func CreateShortURL(c *gin.Context) {
	var input models.URL
	db := c.MustGet("db").(*sql.DB)
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	input.ShortCode = uuid.New().String()[:8]
	db.Exec("INSERT INTO urls (short, original) VALUES (?,?)", input.ShortCode, input.Original)
	c.JSON(http.StatusOK, input)
}

// @Summary Récupère l'URL originale
// @Description Retourne l'URL longue à partir du code court
// @Produce json
// @Param code path string true "Code court"
// @Success 200 {object} map[string]string
// @Router /{code} [get]
func GetOriginalURL(c *gin.Context) {
	shortCode := c.Param("code")
	db := c.MustGet("db").(*sql.DB)
	var url models.URL

	db.Exec("SELECT * FROM url where short=?", shortCode)

	c.JSON(http.StatusOK, url)
}
