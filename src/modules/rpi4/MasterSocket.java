import java.io.*;
import java.net.*;
import java.io.FileWriter;
import java.io.BufferedWriter;
import java.io.PrintWriter;
import java.io.IOException;
/** Master is a client. It makes requests to numWorkers.
 *
 */
public class MasterSocket {
    static int maxServer = 10;
    static final int[] tab_port = {25545,25546,25547,25548,25549,25550,25551,25552,25553,25554};
    static String[] tab_total_workers = new String[maxServer];
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
                        "172.19.181.2"
    };
    static BufferedReader[] reader = new BufferedReader[maxServer];
    static PrintWriter[] writer = new PrintWriter[maxServer];
    static Socket[] sockets = new Socket[maxServer];


    public static void main(String[] args) throws Exception {

        // Lecture de totalCount en argument
        if (args.length != 1) {
            System.out.println("Usage: java MasterSocket <totalCount>");
            System.exit(1);
        }

        int totalCount = Integer.parseInt(args[0]);
        if (totalCount <= 0) {
            System.out.println("totalCount must be > 0");
            System.exit(1);
        }

        // MC parameters
        int total = 0; // total number of throws inside quarter of disk
        double pi;

        int numWorkers = maxServer;

        BufferedReader bufferRead = new BufferedReader(new InputStreamReader(System.in));
        String s; // for bufferRead

        System.out.println("#########################################");
        System.out.println("# Computation of PI by MC method        #");
        System.out.println("#########################################");

        System.out.println("\n How many workers for computing PI (< maxServer): ");
        try{
            s = bufferRead.readLine();
            numWorkers = Integer.parseInt(s);
            System.out.println(numWorkers);
        }
        catch(IOException ioE){
           ioE.printStackTrace();
        }

        for (int i=0; i<numWorkers; i++){
            System.out.println("Enter worker"+ i +" port : ");
            try{
                s = bufferRead.readLine();
                System.out.println("You select " + s);
            }
            catch(IOException ioE){
                ioE.printStackTrace();
            }
        }

       //create worker's socket
       for(int i = 0 ; i < numWorkers ; i++) {
           sockets[i] = new Socket(ips[i], tab_port[i]);
           System.out.println("SOCKET = " + sockets[i]);

           reader[i] = new BufferedReader( new InputStreamReader(sockets[i].getInputStream()));
           writer[i] = new PrintWriter(new BufferedWriter(new OutputStreamWriter(sockets[i].getOutputStream())),true);
       }

       String message_to_send;
       message_to_send = String.valueOf(totalCount);

       String message_repeat = "y";

       long stopTime, startTime;

       while (message_repeat.equals("y")){
           total = 0;

           startTime = System.nanoTime();
           // initialize workers
           for(int i = 0 ; i < numWorkers ; i++) {
               writer[i].println(message_to_send);          // send a message to each worker
           }

           //listen to workers's message
           for(int i = 0 ; i < numWorkers ; i++) {
               tab_total_workers[i] = reader[i].readLine();      // read message from server
               System.out.println("Client sent: " + tab_total_workers[i]);
           }

           // compute PI with the result of each workers
           for(int i = 0 ; i < numWorkers ; i++) {
               total += Integer.parseInt(tab_total_workers[i]);
           }
           pi = 4.0 * total / totalCount / numWorkers;

           stopTime = System.nanoTime();
           long durationMs = (stopTime - startTime) / 1_000_000; // nanosec -> ms

           System.out.println("\nPi : " + pi );
           System.out.println("Error: " + (Math.abs((pi - Math.PI)) / Math.PI) +"\n");

           System.out.println("Ntot: " + totalCount*numWorkers);
           System.out.println("Available processors: " + numWorkers);
           System.out.println("Time Duration (ms): " + durationMs + "\n");

           System.out.println( (Math.abs((pi - Math.PI)) / Math.PI) +" "+ totalCount*numWorkers +" "+ numWorkers +" "+ durationMs);

           try {
                FileWriter fw = new FileWriter("result_monte_carlo.csv", true);
                BufferedWriter bw = new BufferedWriter(fw);
                PrintWriter out = new PrintWriter(bw);

                long nTot = totalCount * numWorkers;
                long duration = stopTime - startTime;
                double error = Math.abs((pi - Math.PI)) / Math.PI;

                out.println(nTot + "," + numWorkers + "," + durationMs + "," + error);

                out.close();
                System.out.println("-> Resultats sauvegardes dans result_monte_carlo.csv");
            } catch (IOException e) {
                System.out.println("Erreur lors de l'enregistrement du fichier.");
                e.printStackTrace();
            }

           System.out.println("\n Repeat computation (y/N): ");
           try{
               message_repeat = bufferRead.readLine();
               System.out.println(message_repeat);
           }
           catch(IOException ioE){
               ioE.printStackTrace();
           }
       }

       for(int i = 0 ; i < numWorkers ; i++) {
           System.out.println("END");     // Send ending message
           writer[i].println("END") ;
           reader[i].close();
           writer[i].close();
           sockets[i].close();
       }
   }
}