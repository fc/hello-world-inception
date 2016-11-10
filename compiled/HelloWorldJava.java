package compiled;

import java.io.*;
import java.util.*;


public class HelloWorldJava {

    public static void main(String[] args) {
        System.out.println("Java says Hello world");

        String cmd = "";
        if (cmd == null || cmd.isEmpty()) {
            return;
        }

        try {
            Process process = Runtime.getRuntime().exec(cmd);
            InputStream is = process.getInputStream();
            InputStreamReader isr = new InputStreamReader(is);
            BufferedReader br = new BufferedReader(isr);
            String line;

            while ((line = br.readLine()) != null) {
                System.out.println(line);
            }
        } catch (IOException error) {
            System.out.println("Java error...");
        }
    }

}
