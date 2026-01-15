import java.io.*;
import java.net.*;

public class MasterSimpsonSocket {

    static final int[] tab_ports = {25550, 25551, 25552, 25553, 25554, 25555, 25556, 25557, 25558, 25559};
    static final String[] ips = {
            "127.0.0.1",
            "127.0.0.1",
            "127.0.0.1",
            "127.0.0.1",
            "172.19.181.1",
            "172.19.181.2",
            "172.19.181.3",
            "172.19.181.4",
            "172.19.181.1",
            "172.19.181.2",
    };

    public static void main(String[] args) throws Exception {

        if (args.length != 4) {
            System.out.println("Usage: java MasterSimpsonSocket <A> <B> <totalN> <worker>");
            System.exit(1);
        }

        double A = Double.parseDouble(args[0]);
        double B = Double.parseDouble(args[1]);
        int totalN = Integer.parseInt(args[2]);
        int workers = Integer.parseInt(args[3]);

        if (totalN <= 0) {
            System.out.println("totalN must be > 0");
            System.exit(1);
        }

        if (workers <= 0) {
            System.out.println("workers must be > 0");
            System.exit(1);
        }

        if (totalN % 2 != 0) {
            System.out.println("totalN must be even. Incrementing by 1.");
            totalN++;
        }

        int nPerWorker = totalN / workers;

        if (nPerWorker % 2 != 0) nPerWorker++;

        System.out.println("===== Simpson Integration =====");
        System.out.println("Interval: [" + A + ", " + B + "]");
        System.out.println("Total sub-intervals: " + totalN);
        System.out.println("Workers: " + workers);
        System.out.println("Sub-intervals per worker: " + nPerWorker);

        Socket[] sockets = new Socket[workers];
        BufferedReader[] in = new BufferedReader[workers];
        PrintWriter[] out = new PrintWriter[workers];

        // 1. On se connecte une seule fois AVANT la boucle
        for (int i = 0; i < workers; i++) {
            sockets[i] = new Socket(ips[i], tab_ports[i]);
            in[i] = new BufferedReader(new InputStreamReader(sockets[i].getInputStream()));
            out[i] = new PrintWriter(sockets[i].getOutputStream(), true);
        }

        double h = (B - A) / workers;

        // Préparation pour la boucle de répétition
        BufferedReader bufferRead = new BufferedReader(new InputStreamReader(System.in));
        String message_repeat = "y";

        // 2. Début de la boucle de répétition
        while (message_repeat.equals("y")) {

            double result = 0.0; // Reset du résultat à 0 à chaque tour

            long start = System.nanoTime();

            // Envoi des données
            for (int i = 0; i < workers; i++) {
                double a = A + i * h;
                double b = a + h;
                out[i].println(a + " " + b + " " + nPerWorker);
            }

            // Réception des résultats
            for (int i = 0; i < workers; i++) {
                result += Double.parseDouble(in[i].readLine());
            }

            long end = System.nanoTime();

            System.out.println("\n===== RESULT =====");
            System.out.println("Integral ~= " + result);
            System.out.println("Execution time (nano): " + (end - start));

            try {
                FileWriter fw = new FileWriter("result_simpson.csv", true);
                BufferedWriter bw = new BufferedWriter(fw);
                PrintWriter csvWriter = new PrintWriter(bw);

                csvWriter.println(totalN + "," + workers + "," + (end - start) + "," + result);

                csvWriter.close();
                System.out.println("-> Résultats sauvegardés dans result_simpson.csv");

            } catch (IOException e) {
                System.out.println("Erreur lors de l'enregistrement du fichier CSV.");
                e.printStackTrace();
            }

            // Demande de répétition
            System.out.println("\n Repeat computation (y/N): ");
            try {
                message_repeat = bufferRead.readLine();
                System.out.println(message_repeat);
            } catch (IOException ioE) {
                ioE.printStackTrace();
            }
        } // Fin de la boucle while

        // 3. Fermeture des connexions APRES la boucle
        for (int i = 0; i < workers; i++) {
            out[i].println("END");
            sockets[i].close();
        }
    }
}