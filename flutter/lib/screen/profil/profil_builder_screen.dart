import 'package:flutter/material.dart';
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:lp1_ciasie_atelier_2/screen/error_screen.dart';
import 'package:lp1_ciasie_atelier_2/screen/loading_screen.dart';
import 'package:lp1_ciasie_atelier_2/screen/profil/profil_screen.dart';
import 'package:provider/provider.dart';

class ProfilBuilderPage extends StatelessWidget {
  const ProfilBuilderPage({super.key});

  // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    return Consumer<SessionProvider>(
      builder: (context, session, child) {
        return FutureBuilder(
          future: session.user,
          builder: (context, snapshot) {
            if (snapshot.hasError) {
              return ExceptionPage(error: snapshot.error);
            } else if (snapshot.hasData) {
              return ProfilPage(user: snapshot.data);
            } else {
              return const LoadingPage();
            }
          },
        );
      },
    );
  }
}
