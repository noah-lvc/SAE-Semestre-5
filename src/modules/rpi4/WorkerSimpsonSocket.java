import java.io.*;
import java.net.*;

public class WorkerSimpsonSocket {

    public static void main(String[] args) throws Exception {

        int port = Integer.parseInt(args[0]);
        ServerSocket server = new ServerSocket(port);
        System.out.println("Worker Simpson listening on port " + port);

        Socket socket = server.accept();
        BufferedReader in = new BufferedReader(new InputStreamReader(socket.getInputStream()));
        PrintWriter out = new PrintWriter(socket.getOutputStream(), true);

        while (true) {
            String msg = in.readLine();
            if (msg.equals("END")) break;

            String[] parts = msg.split(" ");
            double a = Double.parseDouble(parts[0]);
            double b = Double.parseDouble(parts[1]);
            int n = Integer.parseInt(parts[2]);

            double result = Simpson.integrale(a, b, n);
            out.println(result);
        }

        socket.close();
        server.close();
    }
}