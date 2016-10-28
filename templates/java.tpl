package compiled;

public class HelloWorld {

    public static void main(String[] args) {
        System.out.println("Hello world\n");

		Process process = new ProcessBuilder( "%langBin","%langFile").start();
		InputStream is = process.getInputStream();
		InputStreamReader isr = new InputStreamReader(is);
		BufferedReader br = new BufferedReader(isr);
		String line;

		while ((line = br.readLine()) != null) {
		  System.out.println(line);
		}
    }

}
