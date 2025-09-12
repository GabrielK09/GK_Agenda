package main

import (
	"fmt"
	"log"
	"net"
	"os"
	"os/exec"
	"path/filepath"
	"runtime"
)

type Paths struct {
	ApiPath   string
	FrontPath string
}

func getLocalIP() net.IP {
	conn, err := net.Dial("udp", "8.8.8.8:80")
	if err != nil {
		log.Fatal(err)
	}

	defer conn.Close()

	localAddress := conn.LocalAddr().(*net.UDPAddr)

	return localAddress.IP
}

func getPath() Paths {
	var paths Paths
	_, currentDir, _, _ := runtime.Caller(0)
	dir := filepath.Dir(filepath.Dir(currentDir))

	apiPath := filepath.Join(dir, "Admin", "ApiSGAgenda")
	frontPath := filepath.Join(dir, "Admin", "SGAgenda")

	paths.ApiPath = apiPath
	paths.FrontPath = frontPath

	return paths
}

func boot(paths Paths) {
	ip := getLocalIP()
	ipCmd := fmt.Sprintf("--host=%s", ip.String())
	log.Println(ipCmd)
	cmdApi := exec.Command("php", "artisan", "serve", ipCmd)
	cmdApi.Dir = paths.ApiPath
	cmdApi.Stdout = os.Stdout
	cmdApi.Stderr = os.Stderr
	cmdApi.Start()

	cmdFront := exec.Command("quasar", "dev")
	cmdFront.Dir = paths.FrontPath
	cmdFront.Stdout = os.Stdout
	cmdFront.Stderr = os.Stderr
	cmdFront.Start()
}

func main() {
	boot(getPath())
}
