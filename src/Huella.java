import SecuGen.FDxSDKPro.jni.JSGFPLib;
import SecuGen.FDxSDKPro.jni.SGFDxDeviceName;
import SecuGen.FDxSDKPro.jni.SGDeviceInfoParam;
import SecuGen.FDxSDKPro.jni.SGFDxErrorCode;
import SecuGen.FDxSDKPro.jni.SGFingerInfo;
import SecuGen.FDxSDKPro.jni.SGFDxTemplateFormat;

import java.util.Base64;

public class Huella {

    public static void main(String[] args) {
        // Crear una instancia del SDK de SecuGen
        JSGFPLib sgfplib = new JSGFPLib();

        // Validar si la biblioteca JNI se cargó correctamente
        if (sgfplib.jniLoadStatus != SGFDxErrorCode.SGFDX_ERROR_NONE) {
            System.err.println("Error al cargar la biblioteca JNI.");
            return;
        }
        System.out.println("Biblioteca JNI cargada correctamente.");

        // Inicializar el lector de huellas
        long err = sgfplib.Init(SGFDxDeviceName.SG_DEV_AUTO);
        if (err != SGFDxErrorCode.SGFDX_ERROR_NONE) {
            System.err.println("Error al inicializar el dispositivo: Código " + err);
            return;
        }
        System.out.println("Dispositivo inicializado correctamente.");

        // Abrir el dispositivo
        err = sgfplib.OpenDevice(SGFDxDeviceName.SG_DEV_AUTO);
        if (err != SGFDxErrorCode.SGFDX_ERROR_NONE) {
            System.err.println("Error al abrir el dispositivo: Código " + err);
            sgfplib.Close();
            return;
        }
        System.out.println("Dispositivo abierto correctamente.");

        try {
            // Obtener información del dispositivo
            SGDeviceInfoParam deviceInfo = new SGDeviceInfoParam();
            err = sgfplib.GetDeviceInfo(deviceInfo);
            if (err != SGFDxErrorCode.SGFDX_ERROR_NONE) {
                System.err.println("Error al obtener información del dispositivo: Código " + err);
                return;
            }

            System.out.println("Información del dispositivo:");
            System.out.println("- Ancho de imagen: " + deviceInfo.imageWidth);
            System.out.println("- Altura de imagen: " + deviceInfo.imageHeight);
            System.out.println("- Resolución DPI: " + deviceInfo.imageDPI);

            // Capturar la imagen de la huella digital
            byte[] imageBuffer = new byte[deviceInfo.imageWidth * deviceInfo.imageHeight];
            long timeout = 15000; // Tiempo límite en milisegundos
            long quality = 50; // Calidad mínima requerida

            err = sgfplib.GetImageEx(imageBuffer, timeout, quality, 0L); // 0L como identificador de ventana nulo
            if (err != SGFDxErrorCode.SGFDX_ERROR_NONE) {
                System.err.println("Error al capturar imagen: Código " + err);
                return;
            }
            System.out.println("Imagen de huella capturada correctamente.");

            // Crear plantilla de huella digital
            int[] maxTemplateSize = new int[1];
            err = sgfplib.GetMaxTemplateSize(maxTemplateSize);
            if (err != SGFDxErrorCode.SGFDX_ERROR_NONE) {
                System.err.println("Error al obtener el tamaño máximo de plantilla: Código " + err);
                return;
            }

            byte[] templateBuffer = new byte[maxTemplateSize[0]];
            SGFingerInfo fingerInfo = new SGFingerInfo();
            fingerInfo.FingerNumber = 1;
            fingerInfo.ImageQuality = (int) quality;
            fingerInfo.ImpressionType = SGFDxTemplateFormat.TEMPLATE_FORMAT_ANSI378;
            fingerInfo.ViewNumber = 1;

            err = sgfplib.CreateTemplate(fingerInfo, imageBuffer, templateBuffer);
            if (err != SGFDxErrorCode.SGFDX_ERROR_NONE) {
                System.err.println("Error al crear plantilla: Código " + err);
                return;
            }
            System.out.println("Plantilla de huella creada correctamente.");

            // Convertir la imagen y la plantilla a Base64
            String base64Image = Base64.getEncoder().encodeToString(imageBuffer);
            String base64Template = Base64.getEncoder().encodeToString(templateBuffer);

            System.out.println("Imagen en Base64: " + base64Image);
            System.out.println("Plantilla en Base64: " + base64Template);

        } finally {
            // Cerrar el dispositivo y liberar recursos
            err = sgfplib.CloseDevice();
            if (err != SGFDxErrorCode.SGFDX_ERROR_NONE) {
                System.err.println("Error al cerrar el dispositivo: Código " + err);
            } else {
                System.out.println("Dispositivo cerrado correctamente.");
            }
            sgfplib.Close();
            System.out.println("Recursos liberados.");
        }
    }
}
