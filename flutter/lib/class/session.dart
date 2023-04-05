class Session {
  final String id;
  // final String email;
  // final String role;
  String accessToken;
  final String refreshToken;

  Session({
    required this.id,
    // required this.email,
    // required this.role,
    required this.accessToken,
    required this.refreshToken,
  });
}
