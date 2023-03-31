import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'my_app.dart';
import 'provider/session_provider.dart';

void main() {
  runApp(MultiProvider(providers: [
    ChangeNotifierProvider(create: (context) => SessionProvider()),
  ], child: const MyApp()));
}
