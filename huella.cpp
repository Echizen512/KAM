#include <iostream>
#include "SecuGenSDK.h" // Ajusta al nombre real del archivo de cabecera

int main() {
    try {
        // Inicializar el dispositivo
        std::cout << "Inicializando el lector de huellas...\n";
        SGDevice device;
        if (!device.initialize()) {
            std::cerr << "Error al inicializar el lector de huellas.\n";
            return 1;
        }

        // Capturar la huella
        std::cout << "Coloca tu dedo en el lector...\n";
        SGFingerprint fingerprint;
        if (!device.capture(fingerprint)) {
            std::cerr << "Error al capturar la huella.\n";
            return 1;
        }

        // Guardar la imagen de la huella
        const char* filename = "huella.bmp";
        if (!fingerprint.saveToFile(filename)) {
            std::cerr << "Error al guardar la huella en el archivo.\n";
            return 1;
        }

        std::cout << "Huella capturada correctamente y guardada en " << filename << "\n";
        return 0;
    } catch (std::exception& e) {
        std::cerr << "Ocurrió un error: " << e.what() << "\n";
        return 1;
    }
}
