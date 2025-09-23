<?php
$tes = [
  [
    "name" => "Tes Darah",
    "desc" => "Pemeriksaan darah untuk memantau kondisi kesehatan tubuh.",
    "icon" => "Icon 1_Darah.png",
    "sub" => [
      [
        "name" => "Hemoglobin",
        "icon" => "Icon 1_Darah.png",
        "desc" => "Mengukur kadar Hb untuk deteksi anemia.",
        "detail" => [
          "deskripsi" => "Hemoglobin adalah protein dalam sel darah merah yang berfungsi membawa oksigen ke seluruh tubuh.",
          "manfaat" => "Mengetahui kadar hemoglobin untuk deteksi anemia atau masalah darah lainnya.",
          "persiapan" => "Tidak diperlukan persiapan khusus sebelum tes."
        ]
      ],
      [
        "name" => "Golongan Darah",
        "icon" => "Icon 1_Darah.png",
        "desc" => "Mengetahui golongan darah dan rhesus.",
        "detail" => [
          "deskripsi" => "Tes ini menentukan jenis golongan darah ABO dan rhesus untuk keperluan transfusi atau medis.",
          "manfaat" => "Mencegah risiko inkompabilitas darah saat transfusi, pemeriksaan awal kehamilan, dan perlindungan medis lainnya.",
          "persiapan" => "Tidak diperlukan persiapan khusus sebelum tes."
        ]
      ],
      [
        "name" => "Agregasi Trombosit",
        "icon" => "Icon 1_Darah.png",
        "desc" => "Evaluasi fungsi trombosit.",
        "detail" => [
          "deskripsi" => "Agregasi trombosit menilai kemampuan trombosit untuk membentuk bekuan darah.",
          "manfaat" => "Membantu diagnosis kelainan pembekuan darah seperti Von Willebrand, Glanzmann, dan lainnya.",
          "persiapan" => "Puasa minimal 12 jam dan hindari obat-obatan yang mempengaruhi trombosit 10 hari sebelum tes (kecuali sesuai anjuran dokter)."
        ]
      ]
    ]
  ],
  [
    "name" => "Tes Urine",
    "desc" => "Pemeriksaan urine untuk mendeteksi kondisi ginjal atau infeksi.",
    "icon" => "Icon 2_Urine.png",
    "sub" => [
      [
        "name" => "Urine Analysis",
        "icon" => "Icon 2_Urine.png",
        "desc" => "Analisa urine rutin untuk skrining kesehatan.",
        "detail" => [
          "deskripsi" => "Pemeriksaan urine meliputi pemeriksaan fisik, kimia, dan mikroskopis untuk deteksi penyakit ginjal, infeksi, dan kondisi metabolik.",
          "manfaat" => "Mengetahui adanya kelainan ginjal, infeksi saluran kemih, atau penyakit sistemik.",
          "persiapan" => "Tidak ada persiapan khusus; sebaiknya tidak sedang menstruasi."
        ]
      ]
    ]
  ],
  [
    "name" => "Tes Kehamilan",
    "desc" => "Mendeteksi kondisi imun atau infeksi yang berhubungan dengan kehamilan.",
    "icon" => "Icon 3_Kehamilan.png",
    "sub" => [
      [
        "name" => "Anti-Rubella IgG",
        "icon" => "Icon 3_Kehamilan.png",
        "desc" => "Mendeteksi antibodi IgG Rubella.",
        "detail" => [
          "deskripsi" => "Tes ini memeriksa antibodi IgG terhadap Rubella yang berisiko pada ibu hamil.",
          "manfaat" => "Menilai status imun, hasil vaksinasi, dan risiko infeksi Rubella aktif.",
          "persiapan" => "Tidak ada persiapan khusus sebelum tes."
        ]
      ],
      [
        "name" => "Anti-CMV IgG",
        "icon" => "Icon 3_Kehamilan.png",
        "desc" => "Mendeteksi antibodi IgG Cytomegalovirus.",
        "detail" => [
          "deskripsi" => "Tes ini memeriksa antibodi IgG terhadap CMV yang dapat menular dari ibu ke bayi.",
          "manfaat" => "Skrining infeksi CMV, mengetahui infeksi lama atau aktif.",
          "persiapan" => "Tidak ada persiapan khusus sebelum tes."
        ]
      ],
      [
        "name" => "Anti-HSV1 IgG",
        "icon" => "Icon 3_Kehamilan.png",
        "desc" => "Mendeteksi antibodi IgG Herpes Simplex Virus tipe 1.",
        "detail" => [
          "deskripsi" => "Tes ini memeriksa antibodi IgG terhadap HSV1 yang dapat menyebabkan infeksi di area mulut atau genital.",
          "manfaat" => "Mengetahui infeksi HSV1 lama atau aktif.",
          "persiapan" => "Tidak ada persiapan khusus sebelum tes."
        ]
      ]
    ]
  ],
  [
    "name" => "Tes Gigi",
    "desc" => "Pemeriksaan radiologi untuk kesehatan gigi dan mulut.",
    "icon" => "Icon 4_Gigi.png",
    "sub" => [
      [
        "name" => "Dental I CR",
        "icon" => "Icon 4_Gigi.png",
        "desc" => "Foto radiografi gigi untuk deteksi karies.",
        "detail" => [
          "deskripsi" => "Metode pemeriksaan gigi menggunakan sinar X untuk melihat bagian dalam gigi dan gusi.",
          "manfaat" => "Mendeteksi masalah gigi seperti karies atau infeksi akar.",
          "persiapan" => "Tidak ada persiapan khusus sebelum tes."
        ]
      ],
      [
        "name" => "Panoramic",
        "icon" => "Icon 4_Gigi.png",
        "desc" => "Foto rontgen seluruh rahang.",
        "detail" => [
          "deskripsi" => "Pemeriksaan rontgen untuk melihat jaringan lunak dan rahang atas bawah dalam satu gambar.",
          "manfaat" => "Menilai kondisi tulang rahang, gigi impaksi, atau kelainan mulut lainnya.",
          "persiapan" => "Tidak ada persiapan khusus sebelum tes."
        ]
      ],
      [
        "name" => "Water’s Foto",
        "icon" => "Icon 4_Gigi.png",
        "desc" => "Foto rontgen sinus maksilaris.",
        "detail" => [
          "deskripsi" => "Foto rontgen untuk mengevaluasi cairan pada sinus paranasal.",
          "manfaat" => "Mendiagnosis sinusitis atau kelainan rongga sinus.",
          "persiapan" => "Melepas benda logam pada area yang akan diperiksa."
        ]
      ]
    ]
  ]
];
?>
