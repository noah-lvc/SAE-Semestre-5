public class Simpson {

    /**
     * Calcul de l'intégrale de a à b par la méthode de Simpson en utilisant n sous-intégrales
     *
     */
    public static double integrale(double a, double b, int n) {
        if (n % 2 != 0) {
            throw new IllegalArgumentException("n must be even");
        }

        //Longeur des sous-intervalles
        double h = (b - a) / n;

        double sum = f(a) + f(b);

        for (int i = 1; i < n; i++) {
            double x = a + i * h;
            sum += (i % 2 == 0 ? 2 : 4) * f(x);
        }

        return sum * h / 3.0;
    }

    /**
     * Fonction de Gauss-Laplace
     *
     */
    private static double f(double x) {
        return Math.exp(-x * x);
    }
}